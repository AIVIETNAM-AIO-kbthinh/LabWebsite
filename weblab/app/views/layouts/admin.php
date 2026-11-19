<?php
/** @var string $contentForLayout */
/** @var string $current */
$current = $current ?? '';
$title   = $page_title ?? 'Admin';
$flashes = flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> | <?= e(SITE_NAME) ?></title>
<link rel="icon" type="image/png" href="<?= url('/public/assets/logo-dark.png') ?>">
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= url('/admin/admin.css') ?>">
<link rel="stylesheet" href="<?= url('/admin/admin-extra.css') ?>">
</head>
<body class="admin-body">

<aside class="admin-sidebar">
    <div class="admin-logo admin-brand-mark sidebar-brand-mark">
        <img src="<?= url('/public/assets/logo-dark.png') ?>" alt="ISI Lab" class="admin-brand-logo-img">
    </div>
    <nav class="admin-nav">
        <a href="<?= url('/admin') ?>"               class="admin-nav-item <?= $current==='dashboard'?'active':'' ?>"><i class="fas fa-gauge-high"></i> Dashboard</a>
        <div class="admin-nav-divider">Content</div>
        <a href="<?= url('/admin/research') ?>"      class="admin-nav-item <?= $current==='research'?'active':'' ?>"><i class="fas fa-flask"></i> Research Areas</a>
        <a href="<?= url('/admin/members') ?>"       class="admin-nav-item <?= $current==='members'?'active':'' ?>"><i class="fas fa-users"></i> Members</a>
        <a href="<?= url('/admin/projects') ?>"      class="admin-nav-item <?= $current==='projects'?'active':'' ?>"><i class="fas fa-folder-open"></i> Projects</a>
        <a href="<?= url('/admin/publications') ?>"  class="admin-nav-item <?= $current==='publications'?'active':'' ?>"><i class="fas fa-book"></i> Publications</a>
        <a href="<?= url('/admin/news') ?>"          class="admin-nav-item <?= $current==='news'?'active':'' ?>"><i class="fas fa-newspaper"></i> News</a>
        <a href="<?= url('/admin/gallery') ?>"       class="admin-nav-item <?= $current==='gallery'?'active':'' ?>"><i class="fas fa-images"></i> Gallery</a>
        <div class="admin-nav-divider">System</div>
        <a href="<?= url('/admin/messages') ?>"      class="admin-nav-item <?= $current==='messages'?'active':'' ?>"><i class="fas fa-inbox"></i> Messages</a>
        <a href="<?= url('/admin/settings') ?>"      class="admin-nav-item <?= $current==='settings'?'active':'' ?>"><i class="fas fa-gear"></i> Settings</a>
        <a href="<?= url('/admin/account') ?>"       class="admin-nav-item <?= $current==='account'?'active':'' ?>"><i class="fas fa-user-lock"></i> Admin Account</a>
        <a href="<?= url('/') ?>"                    class="admin-nav-item" target="_blank"><i class="fas fa-globe"></i> View Site</a>
        <a href="<?= url('/admin/logout') ?>"        class="admin-nav-item danger"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
</aside>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1 class="admin-page-title"><?= e($title) ?></h1>
        </div>
        <div class="admin-user">
            <i class="fas fa-user-shield"></i>
            <span><?= e($_SESSION['admin_user'] ?? 'admin') ?></span>
        </div>
    </header>

    <?php if (!empty($flashes)): ?>
    <div class="admin-flash-stack">
        <?php foreach ($flashes as $f): ?>
            <div class="admin-flash admin-flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="admin-content">
        <?= $contentForLayout ?>
    </div>
</main>

</body>
</html>
