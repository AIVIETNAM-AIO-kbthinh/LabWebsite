# ============================================================
#  Re-enable the mysql_native_password plugin on MySQL 8.4
#  (the 'isilabadmin' account in config.php uses it).
#  MUST be run from an *Administrator* PowerShell:
#     powershell -ExecutionPolicy Bypass -File G:\LabWebsite\dev\enable-mysql-native-password.ps1
# ============================================================
$ErrorActionPreference = 'Stop'
$ini     = 'C:\ProgramData\MySQL\MySQL Server 8.4\my.ini'
$service = 'MySQL84'

$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole(
    [Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) { throw 'Please run this script from an Administrator PowerShell.' }

# Keep the original bytes (file has no BOM; MySQL dislikes a BOM)
$enc   = [Text.Encoding]::GetEncoding(1252)
$text  = [IO.File]::ReadAllText($ini, $enc)

if ($text -match '(?m)^\s*mysql[_-]native[_-]password\s*=\s*ON') {
    Write-Host 'mysql_native_password=ON is already set.'
} else {
    $backup = "$ini.bak-" + (Get-Date -Format 'yyyyMMdd-HHmmss')
    Copy-Item $ini $backup
    Write-Host "Backup: $backup"
    $text = [regex]::Replace($text, '(?m)^\[mysqld\]\r?$', "[mysqld]`r`nmysql_native_password=ON", 1)
    [IO.File]::WriteAllText($ini, $text, $enc)
    Write-Host 'Added mysql_native_password=ON under [mysqld].'
}

Write-Host "Restarting service $service ..."
Restart-Service -Name $service -Force
Get-Service -Name $service | Format-Table Name, Status -AutoSize
Write-Host 'Done. You can close this window.'
