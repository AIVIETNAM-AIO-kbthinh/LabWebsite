<?php
/**
 * =====================================================
 *  ISI LAB v3 - Global Configuration
 *  Edit the values below to match your server.
 * =====================================================
 */

// ----- Database -----
define('DB_HOST',    'localhost');
define('DB_PORT',    '3306');
define('DB_NAME',    'isilab');
define('DB_USER',    'isilabadmin');
define('DB_PASS',    'isilab@2026');
define('DB_CHARSET', 'utf8mb4');

// ----- Site -----
define('SITE_NAME',  'ISI Lab');
define('SITE_URL',   '');
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('UPLOAD_URL', SITE_URL . '/uploads');

// ----- Security -----
define('SESSION_NAME',   'ISILAB_SESS');
define('CSRF_TOKEN_KEY', 'csrf_token');

// ----- Environment -----
define('APP_DEBUG', false);
define('APP_TZ',    'Asia/Ho_Chi_Minh');

date_default_timezone_set(APP_TZ);

// Error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}