# ============================================================
#  Run the ISI Lab website locally.
#  Usage (from G:\LabWebsite):   .\dev\run-local.ps1  [-Port 8000] [-ResetDb]
#
#  - Starts a private MySQL 8.4 instance on 127.0.0.1:3307 (data in dev\mysql\data),
#    independent from the MySQL84 Windows service. First run creates it, imports
#    weblab\database\isilab.sql and the 'isilabadmin' user from config.php.
#  - Starts PHP's built-in server with dev\router.php (mimics .htaccess).
#  -ResetDb wipes the dev database and re-imports isilab.sql.
# ============================================================
param([int]$Port = 8000, [switch]$ResetDb)
$ErrorActionPreference = 'Stop'

$root    = Split-Path $PSScriptRoot -Parent
$myDir   = Join-Path $PSScriptRoot 'mysql'
$myIni   = Join-Path $myDir 'my.ini'
$dataDir = Join-Path $myDir 'data'
$myBin   = 'C:\Program Files\MySQL\MySQL Server 8.4\bin'
$mysqld  = Join-Path $myBin 'mysqld.exe'
$mysql   = Join-Path $myBin 'mysql.exe'
$dbPort  = 3307

$php = Join-Path $env:LOCALAPPDATA 'Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe'
if (-not (Test-Path $php)) { $php = (Get-Command php -ErrorAction SilentlyContinue).Source }
if (-not $php)             { throw 'PHP not found. Install it with: winget install PHP.PHP.8.3' }
if (-not (Test-Path $mysqld)) { throw "MySQL 8.4 not found at $myBin" }

function Test-DevDb { & $mysql -h 127.0.0.1 -P $dbPort -u root -e 'SELECT 1' 2>$null | Out-Null; return $LASTEXITCODE -eq 0 }

# ---------- MySQL (dev instance) ----------
$fresh = $false
if ($ResetDb -and (Test-Path $dataDir)) {
    if (Test-DevDb) { & $mysql -h 127.0.0.1 -P $dbPort -u root -e 'SHUTDOWN' 2>$null; Start-Sleep 3 }
    Remove-Item $dataDir -Recurse -Force
}
if (-not (Test-Path $dataDir)) {
    Write-Host 'Initializing dev MySQL data directory...'
    & $mysqld --defaults-file="$myIni" --initialize-insecure
    $fresh = $true
}
if (-not (Test-DevDb)) {
    Write-Host "Starting dev MySQL on 127.0.0.1:$dbPort ..."
    Start-Process -FilePath $mysqld -ArgumentList "--defaults-file=`"$myIni`"" -WindowStyle Hidden
    for ($i = 0; $i -lt 30 -and -not (Test-DevDb); $i++) { Start-Sleep 1 }
    if (-not (Test-DevDb)) { throw "Dev MySQL did not start. See $myDir\mysqld.log" }
}
if ($fresh) {
    Write-Host 'Importing weblab\database\isilab.sql ...'
    Get-Content (Join-Path $root 'weblab\database\isilab.sql') -Raw -Encoding UTF8 |
        & $mysql -h 127.0.0.1 -P $dbPort -u root --default-character-set=utf8mb4
    & $mysql -h 127.0.0.1 -P $dbPort -u root -e @"
CREATE USER IF NOT EXISTS 'isilabadmin'@'localhost' IDENTIFIED BY 'isilab@2026';
CREATE USER IF NOT EXISTS 'isilabadmin'@'127.0.0.1' IDENTIFIED BY 'isilab@2026';
GRANT ALL ON isilab.* TO 'isilabadmin'@'localhost';
GRANT ALL ON isilab.* TO 'isilabadmin'@'127.0.0.1';
"@
}

# ---------- PHP built-in server ----------
$env:ISILAB_DB_PORT = "$dbPort"
Write-Host ''
Write-Host "PHP   : $php"
Write-Host "Site  : http://localhost:$Port"
Write-Host "20/11 : http://localhost:$Port/20-11"
Write-Host 'Press Ctrl+C to stop the web server (dev MySQL keeps running in the background).'
& $php -S "localhost:$Port" -t (Join-Path $root 'weblab') (Join-Path $PSScriptRoot 'router.php')
