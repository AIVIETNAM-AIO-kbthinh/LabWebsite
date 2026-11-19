<?php
/** Blank layout used by the login page */
$title = $page_title ?? 'Login';
$flashes = flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?></title>
<link rel="icon" type="image/png" href="<?= url('/public/assets/logo-dark.png') ?>">
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= url('/admin/admin.css') ?>">
<link rel="stylesheet" href="<?= url('/admin/admin-extra.css') ?>">
</head>
<body class="admin-login-body">
<?php if (!empty($flashes)): ?>
<div class="admin-flash-stack" style="position:fixed;top:20px;right:20px;z-index:9999">
    <?php foreach ($flashes as $f): ?>
        <div class="admin-flash admin-flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?= $contentForLayout ?>
</body>
</html>
