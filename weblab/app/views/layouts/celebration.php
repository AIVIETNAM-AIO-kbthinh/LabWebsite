<?php
/**
 * Standalone layout for celebration pages (e.g. 20/11 Teachers' Day).
 * Keeps the lab's theme preference (light/dark) and language switch,
 * but uses its own festive look instead of the main navbar/footer.
 *
 * @var string $contentForLayout
 * @var string|null $page_title
 */
$title = $page_title ?? SITE_NAME;
$lang  = current_lang();

$nextLang      = $lang === 'vi' ? 'en' : 'vi';
$requestPath   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$langQuery     = $_GET;
$langQuery['lang'] = $nextLang;
$langSwitchUrl = $requestPath . '?' . http_build_query($langQuery);

$cssFile = __DIR__ . '/../../../public/css/teachers-day.css';
$jsFile  = __DIR__ . '/../../../public/js/teachers-day.js';
$cssVer  = is_file($cssFile) ? (string)filemtime($cssFile) : '1';
$jsVer   = is_file($jsFile)  ? (string)filemtime($jsFile)  : '1';
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= $lang === 'vi' ? 'ISI Lab chúc mừng Ngày Nhà giáo Việt Nam 20/11 - Tri ân các thầy cô.' : "ISI Lab celebrates Vietnamese Teachers' Day (20 November) - thank you, teachers." ?>">
    <link rel="icon" type="image/png" href="<?= url('/public/assets/logo-light.png') ?>">

    <!-- Apply saved theme before paint (shared with the main site) -->
    <script>
        document.documentElement.classList.add('td-js');
        try { document.documentElement.setAttribute('data-theme', localStorage.getItem('isilab-theme') || 'light'); } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Dancing+Script:wght@500;700&family=Playfair+Display:ital,wght@0,600;0,800;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= url('/public/css/teachers-day.css') ?>?v=<?= e($cssVer) ?>">
</head>
<body class="td-body">

    <canvas id="tdPetals" class="td-petals" aria-hidden="true"></canvas>

    <!-- Top bar -->
    <header class="td-topbar" id="tdTopbar">
        <a href="<?= url('/') ?>" class="td-brand" aria-label="ISI Lab">
            <img src="<?= url('/public/assets/logo-light.png') ?>" alt="ISI Lab" class="td-logo td-logo-light">
            <img src="<?= url('/public/assets/logo-dark.png') ?>"  alt="ISI Lab" class="td-logo td-logo-dark">
        </a>
        <div class="td-topbar-actions">
            <a class="td-chip" href="<?= e($langSwitchUrl) ?>" title="<?= $lang === 'vi' ? 'Switch to English' : 'Chuyển sang tiếng Việt' ?>">
                <?= strtoupper($nextLang) ?>
            </a>
            <button class="td-chip td-chip-icon" id="tdThemeToggle" type="button" aria-label="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>
            <a href="<?= url('/') ?>" class="td-btn td-btn-ghost td-btn-sm">
                <i class="fas fa-house"></i>
                <span><?= $lang === 'vi' ? 'Trang chủ' : 'Home' ?></span>
            </a>
        </div>
    </header>

    <!-- GSAP ScrollSmoother wrapper (fixed elements stay outside) -->
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <?= $contentForLayout ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollSmoother.min.js"></script>
    <script src="<?= url('/public/js/teachers-day.js') ?>?v=<?= e($jsVer) ?>"></script>
</body>
</html>
