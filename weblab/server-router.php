<?php
/**
 * Router for PHP built-in dev server (development only).
 *  Usage:  php -S localhost:8000 server-router.php
 *
 * In production we don't need this - Apache + .htaccess handles routing.
 */
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$file = __DIR__ . $uri;

// Serve real files (assets, uploads, etc.) directly.
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}
// Everything else goes to front controller.
require __DIR__ . '/index.php';
