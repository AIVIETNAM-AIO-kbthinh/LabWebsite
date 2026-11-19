<?php
/** @var string $contentForLayout */
/** @var string $current */
/** @var string|null $page_title */
$current = $current ?? '';
$title   = $page_title ?? (SITE_NAME . ' - Intelligent System and IoT Laboratory');
$lang    = current_lang();
$flashes = flash_get();

// Build a server-side language-switch URL. This keeps database content and
// interface text synchronized even when an old JavaScript file is cached.
$nextLang = $lang === 'vi' ? 'en' : 'vi';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$langQuery = $_GET;
$langQuery['lang'] = $nextLang;
$langSwitchUrl = $requestPath . '?' . http_build_query($langQuery);
$i18nFile = __DIR__ . '/../../../public/js/i18n.js';
$i18nVersion = is_file($i18nFile) ? (string)filemtime($i18nFile) : '1';
$mainJsFile = __DIR__ . '/../../../public/js/main.js';
$animJsFile = __DIR__ . '/../../../public/js/site-anim.js';
$mainJsVersion = is_file($mainJsFile) ? (string)filemtime($mainJsFile) : '1';
$animJsVersion = is_file($animJsFile) ? (string)filemtime($animJsFile) : '1';
$siteCssFile = __DIR__ . '/../../../public/css/site.css';
$siteCssVersion = is_file($siteCssFile) ? (string)filemtime($siteCssFile) : '1';
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <meta name="description" content="ISI Lab - Intelligent System and IoT Laboratory at VNU International School. Research in AI, IoT, Robotics, UAV, and Automation.">
    <link rel="icon" type="image/png" href="<?= url('/public/assets/logo-light.png') ?>">
    <link rel="shortcut icon" href="<?= url('/public/assets/logo.svg') ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- GSAP: hide [data-aos] elements until GSAP sets their start state.
         Safety net: show everything if scripts fail to load. -->
    <script>
        document.documentElement.classList.add('gsap-pending');
        setTimeout(function () { document.documentElement.classList.remove('gsap-pending'); }, 3000);
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= url('/public/css/style.css') ?>">
    <link rel="stylesheet" href="<?= url('/public/css/site.css') ?>?v=<?= e($siteCssVersion) ?>">

    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
</head>
<body class="page-<?= e($current) ?>">

    <!-- Particle Background -->
    <canvas id="particle-canvas"></canvas>

    <!-- Cursor Glow -->
    <div class="cursor-glow" id="cursorGlow"></div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="<?= url('/') ?>" class="nav-logo" aria-label="ISI Lab Home">
                <img src="<?= url('/public/assets/logo-light.png') ?>" alt="ISI Lab" class="header-logo-img logo-light">
                <img src="<?= url('/public/assets/logo-dark.png') ?>" alt="ISI Lab" class="header-logo-img logo-dark">
            </a>

            <ul class="nav-menu" id="navMenu">
                <li><a href="<?= url('/') ?>"             class="nav-link <?= nav_active('home',$current) ?>"         data-i18n="nav.home">Home</a></li>
                <li><a href="<?= url('/research') ?>"     class="nav-link <?= nav_active('research',$current) ?>"     data-i18n="nav.research">Research</a></li>
                <li><a href="<?= url('/members') ?>"      class="nav-link <?= nav_active('members',$current) ?>"      data-i18n="nav.members">Members</a></li>
                <li><a href="<?= url('/projects') ?>"     class="nav-link <?= nav_active('projects',$current) ?>"     data-i18n="nav.projects">Projects</a></li>
                <li><a href="<?= url('/publications') ?>" class="nav-link <?= nav_active('publications',$current) ?>" data-i18n="nav.publications">Publications</a></li>
                <li><a href="<?= url('/news') ?>"         class="nav-link <?= nav_active('news',$current) ?>"         data-i18n="nav.news">News</a></li>
                <li><a href="<?= url('/gallery') ?>"      class="nav-link <?= nav_active('gallery',$current) ?>"      data-i18n="nav.gallery">Gallery</a></li>
                <li><a href="<?= url('/contact') ?>"      class="nav-link <?= nav_active('contact',$current) ?>"      data-i18n="nav.contact">Contact</a></li>
            </ul>

            <div class="nav-actions">
                <a class="lang-toggle" id="langSwitch" href="<?= e($langSwitchUrl) ?>" title="<?= $lang === 'vi' ? 'Switch to English' : 'Chuyển sang tiếng Việt' ?>" aria-label="<?= $lang === 'vi' ? 'Switch to English' : 'Chuyển sang tiếng Việt' ?>">
                    <span class="lang-current"><?= strtoupper($lang) ?></span>
                </a>
                <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="hamburger" id="hamburger">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <?php if (!empty($flashes)): ?>
    <div class="flash-stack">
        <?php foreach ($flashes as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- GSAP ScrollSmoother: everything that scrolls lives inside here.
         Fixed elements (navbar, canvas, modal, back-to-top...) stay outside. -->
    <div id="smooth-wrapper">
    <div id="smooth-content">

    <?= $contentForLayout ?>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-grid"></div>
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <div class="footer-logo">
                        <div class="logo-icon"><i class="fas fa-microchip"></i></div>
                        <div class="logo-text">
                            <span class="logo-main">ISI</span>
                            <span class="logo-sub">LAB</span>
                        </div>
                    </div>
                    <p data-i18n="footer.about">Intelligent System and IoT Laboratory - Pioneering research in AI, IoT, Robotics and UAV systems.</p>
                </div>
                <div class="footer-col">
                    <h4 data-i18n="footer.links">Quick Links</h4>
                    <ul>
                        <li><a href="<?= url('/') ?>"        data-i18n="nav.home">Home</a></li>
                        <li><a href="<?= url('/research') ?>" data-i18n="nav.research">Research</a></li>
                        <li><a href="<?= url('/members') ?>" data-i18n="nav.members">Members</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 data-i18n="footer.explore">Explore</h4>
                    <ul>
                        <li><a href="<?= url('/projects') ?>"     data-i18n="nav.projects">Projects</a></li>
                        <li><a href="<?= url('/publications') ?>" data-i18n="nav.publications">Publications</a></li>
                        <li><a href="<?= url('/news') ?>"         data-i18n="nav.news">News</a></li>
                        <li><a href="<?= url('/gallery') ?>"      data-i18n="nav.gallery">Gallery</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 data-i18n="footer.contact">Contact</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> <span><?= e(setting($lang==='vi'?'contact_address_vi':'contact_address_en')) ?></span></li>
                        <li><i class="fas fa-envelope"></i> <span><?= e(setting('contact_email')) ?></span></li>
                        <li><i class="fas fa-phone"></i> <span><?= e(setting('contact_phone')) ?></span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> ISI Lab. <span data-i18n="footer.rights">All rights reserved.</span></p>
                <p><a href="<?= url('/admin/login') ?>" class="admin-link"><i class="fas fa-lock"></i> <span data-i18n="footer.admin">Admin</span></a></p>
            </div>
        </div>
    </footer>

    </div><!-- /#smooth-content -->
    </div><!-- /#smooth-wrapper -->

    <!-- Back to top -->
    <button class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></button>

    <!-- Image Modal -->
    <div class="image-modal" id="imageModal">
        <span class="modal-close">&times;</span>
        <img id="modalImg" alt="">
        <div class="modal-caption" id="modalCaption"></div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollSmoother.min.js"></script>
    <script>
        window.SITE_URL = <?= json_encode(rtrim(SITE_URL,'/')) ?>;
        window.ISILAB_LANG = <?= json_encode($lang) ?>;
    </script>
    <script src="<?= url('/public/js/i18n.js') ?>?v=<?= e($i18nVersion) ?>"></script>
    <script src="<?= url('/public/js/particles.js') ?>"></script>
    <script src="<?= url('/public/js/main.js') ?>?v=<?= e($mainJsVersion) ?>"></script>
    <script src="<?= url('/public/js/site-anim.js') ?>?v=<?= e($animJsVersion) ?>"></script>
</body>
</html>
