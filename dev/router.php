<?php
/**
 * Local development router for PHP's built-in server.
 * Mirrors weblab/.htaccess (Apache mod_rewrite) so clean URLs work:
 *
 *   php -S localhost:8000 -t weblab dev/router.php
 *
 * NOT for production - the real server uses Apache/IIS + .htaccess.
 */
$docRoot = $_SERVER['DOCUMENT_ROOT'];

// Local DB override without touching weblab/config.php: when ISILAB_DB_PORT is
// set (dev MySQL on 3307), define DB_HOST/DB_PORT first; config.php's own
// define() then becomes a no-op, and that "already defined" warning is muted.
if ($devPort = getenv('ISILAB_DB_PORT')) {
    define('DB_HOST', '127.0.0.1');
    define('DB_PORT', $devPort);
    set_error_handler(
        static fn(int $no, string $msg): bool => str_contains($msg, 'already defined'),
        E_WARNING
    );
}
$path    = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

// Folders/files that the .htaccess files deny ("Require all denied")
if (preg_match('~^/(app|database)(/|$)|^/(config\.php|composer\.(json|lock)|\.user\.ini|\.htaccess)$~i', $path)) {
    http_response_code(403);
    echo 'Forbidden';
    return true;
}

// Admin pages always go through the front controller (except its CSS files)
$isAdminCss = (bool)preg_match('~^/admin/(admin|admin-extra)\.css$~', $path);
if (!$isAdminCss && preg_match('~^/admin(/|$)~', $path)) {
    require $docRoot . '/index.php';
    return true;
}

// Real static files are served as-is by the built-in server
$file = realpath($docRoot . $path);
if ($file !== false && is_file($file) && str_starts_with($file, realpath($docRoot))) {
    return false;   // built-in server serves it (and executes .php files)
}

// Everything else -> front controller
$_SERVER['SCRIPT_NAME'] = '/index.php';
require $docRoot . '/index.php';
return true;
