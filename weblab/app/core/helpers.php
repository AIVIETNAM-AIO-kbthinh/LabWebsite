<?php
/**
 * Global helper functions
 */

/** HTML-safe echo */
function e($v): string {
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Current language ('en' | 'vi') shared by PHP, session and browser cookie */
function current_lang(): string {
    static $resolved = null;
    if ($resolved !== null) return $resolved;

    $isValid = static fn($value): bool => $value === 'en' || $value === 'vi';

    // The URL parameter is authoritative for the request immediately after
    // the user clicks the language switch.
    $requested = $_GET['lang'] ?? null;
    if (is_string($requested)) $requested = strtolower(trim($requested));

    $cookieLang  = $_COOKIE['isilab-lang'] ?? null;
    $sessionLang = $_SESSION['isilab_lang'] ?? null;

    if ($isValid($requested)) {
        $resolved = $requested;
    } elseif ($isValid($cookieLang)) {
        $resolved = $cookieLang;
    } elseif ($isValid($sessionLang)) {
        $resolved = $sessionLang;
    } else {
        $resolved = 'en';
    }

    // Keep all server-side and browser-side language stores synchronized.
    $_SESSION['isilab_lang'] = $resolved;
    $_COOKIE['isilab-lang']  = $resolved;

    if (!headers_sent()) {
        setcookie('isilab-lang', $resolved, [
            'expires'  => time() + 60 * 60 * 24 * 365,
            'path'     => '/',
            'samesite' => 'Lax',
        ]);
    }

    return $resolved;
}

/** Pick localised field ($row['title_en'] / $row['title_vi']) */
function tr(array $row, string $field): string {
    $lang = current_lang();

    $localizedKey = "{$field}_{$lang}";
    $englishKey   = "{$field}_en";

    $localizedValue = trim((string)($row[$localizedKey] ?? ''));

    if ($localizedValue !== '') {
        return $localizedValue;
    }

    $englishValue = trim((string)($row[$englishKey] ?? ''));

    if ($englishValue !== '') {
        return $englishValue;
    }

    return trim((string)($row[$field] ?? ''));
}

/** Build absolute URL inside the site */
function url(string $path = ''): string {
    $base = rtrim(SITE_URL, '/');
    $path = '/' . ltrim($path, '/');
    return $base . $path;
}

/** Active link helper */
function nav_active(string $route, string $current): string {
    return $route === $current ? 'active' : '';
}

/** Start session with secure defaults */
function start_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** Generate / fetch CSRF token */
function csrf_token(): string {
    start_session();
    if (empty($_SESSION[CSRF_TOKEN_KEY])) {
        $_SESSION[CSRF_TOKEN_KEY] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_KEY];
}
function csrf_field(): string {
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}
function csrf_check(): bool {
    start_session();
    $t = $_POST['_token'] ?? '';
    return is_string($t) && hash_equals($_SESSION[CSRF_TOKEN_KEY] ?? '', $t);
}

/** Slug generator */
function slugify(string $text): string {
    $text = preg_replace('~[^\\pL\\d]+~u', '-', $text);
    $text = iconv('utf-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'item-' . bin2hex(random_bytes(4));
}

/** Flash messages */
function flash_set(string $type, string $msg): void {
    start_session();
    $_SESSION['_flash'][] = ['type' => $type, 'msg' => $msg];
}
function flash_get(): array {
    start_session();
    $f = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $f;
}

/** Redirect */
function redirect(string $to): void {
    header('Location: ' . $to);
    exit;
}

/** Settings cache (loaded once per request) */
function settings(): array {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        $rows = Database::get()->all('SELECT `key_name`,`value` FROM settings');
        foreach ($rows as $r) $cache[$r['key_name']] = $r['value'];
    }
    return $cache;
}

/** Setting getter */
function setting(string $key, $default = ''): string {
    $s = settings();
    return $s[$key] ?? $default;
}

/** Date formatter */
function fmt_date($d): string {
    if (!$d) return '';
    $ts = strtotime($d);
    if (!$ts) return (string)$d;
    $lang = current_lang();
    if ($lang === 'vi') {
        return date('d/m/Y', $ts);
    }
    return date('M j, Y', $ts);
}

/** Format author / venue / etc. safely (preserves line breaks) */
function nl2br_e(?string $s): string {
    return nl2br(e($s));
}

/** Check admin logged in */
function is_admin(): bool {
    start_session();
    return !empty($_SESSION['admin_id']);
}

/** Require admin (used in admin pages) */
function require_admin(): void {
    if (!is_admin()) {
        redirect(url('/admin/login'));
    }
}

/** Render view inside layout */
function view(string $view, array $data = [], string $layout = 'site'): void {
    extract($data, EXTR_SKIP);
    $viewFile   = __DIR__ . '/../views/' . $view . '.php';
    $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
    if (!file_exists($viewFile)) {
        http_response_code(500);
        die('View not found: ' . e($view));
    }
    ob_start();
    include $viewFile;
    $contentForLayout = ob_get_clean();
    if (!file_exists($layoutFile)) {
        echo $contentForLayout;
        return;
    }
    include $layoutFile;
}

/** Simple JSON response */
function json_response($data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** Image URL helper - returns absolute if already http, else relative to /uploads */
function img_url(?string $img, string $fallback = ''): string {
    if (!$img) return $fallback;
    if (preg_match('~^https?://~i', $img)) return $img;
    if (str_starts_with($img, '/')) return $img;
    return UPLOAD_URL . '/' . ltrim($img, '/');
}
