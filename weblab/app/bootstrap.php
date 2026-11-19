<?php
/**
 * Application bootstrap: loads config, core classes, helpers,
 * and defines all routes.
 */

require __DIR__ . '/../config.php';

// Autoload core / controllers
spl_autoload_register(function ($class) {
    foreach (['core', 'controllers', 'models'] as $dir) {
        $p = __DIR__ . "/{$dir}/{$class}.php";
        if (file_exists($p)) { require $p; return; }
    }
});

require __DIR__ . '/core/helpers.php';

start_session();

$r = new Router();

// =================== PUBLIC SITE ===================
$r->get('/',                     [PageController::class, 'home']);
$r->get('/about',                [PageController::class, 'about']);
$r->get('/research',             [PageController::class, 'research']);
$r->get('/members',              [PageController::class, 'members']);
$r->get('/members/{slug}',       [PageController::class, 'memberDetail']);
$r->get('/projects',             [PageController::class, 'projects']);
$r->get('/projects/{slug}',      [PageController::class, 'projectDetail']);
$r->get('/publications',         [PageController::class, 'publications']);
$r->get('/news',                 [PageController::class, 'news']);
$r->get('/news/{slug}',          [PageController::class, 'newsDetail']);
$r->get('/gallery',              [PageController::class, 'gallery']);
$r->get('/contact',              [PageController::class, 'contact']);
$r->post('/contact',             [PageController::class, 'contactSubmit']);
$r->get('/20-11',                [PageController::class, 'teachersDay']);
$r->get('/teachers-day',         [PageController::class, 'teachersDay']);

// =================== JSON API ======================
$r->get('/api/gallery',          [ApiController::class, 'gallery']);
$r->get('/api/stats',            [ApiController::class, 'stats']);
$r->get('/api/search',           [ApiController::class, 'search']);

// =================== ADMIN AUTH ====================
$r->get ('/admin/login',         [AuthController::class, 'loginForm']);
$r->post('/admin/login',         [AuthController::class, 'login']);
$r->get ('/admin/logout',        [AuthController::class, 'logout']);

// =================== ADMIN DASHBOARD ===============
$r->get('/admin',                [AdminController::class, 'dashboard']);
$r->get('/admin/settings',       [AdminController::class, 'settings']);
$r->post('/admin/settings',      [AdminController::class, 'settingsSave']);
$r->get('/admin/account',        [AdminController::class, 'account']);
$r->post('/admin/account',       [AdminController::class, 'accountSave']);
$r->get('/admin/messages',       [AdminController::class, 'messages']);
$r->post('/admin/messages/{id}/read',   [AdminController::class, 'messageMarkRead']);
$r->post('/admin/messages/{id}/delete', [AdminController::class, 'messageDelete']);

// =================== ADMIN GENERIC CRUD =============
// Sections share the same controller actions, dispatched by name.
$adminSections = ['research', 'members', 'projects', 'publications', 'news', 'gallery'];
foreach ($adminSections as $sec) {
    $r->get ("/admin/{$sec}",           fn() => (new AdminController())->listAny($sec));
    $r->get ("/admin/{$sec}/new",       fn() => (new AdminController())->formAny($sec));
    $r->post("/admin/{$sec}/new",       fn() => (new AdminController())->saveAny($sec));
    $r->get ("/admin/{$sec}/{id}/edit", fn($id) => (new AdminController())->formAny($sec, $id));
    $r->post("/admin/{$sec}/{id}/edit", fn($id) => (new AdminController())->saveAny($sec, $id));
    $r->post("/admin/{$sec}/{id}/delete", fn($id) => (new AdminController())->deleteAny($sec, $id));
}

$r->dispatch();
