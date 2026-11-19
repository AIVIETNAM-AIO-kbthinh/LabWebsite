<?php
/** @var array $research */
/** @var array $featured */
/** @var array $recent_news */
/** @var array $leaders */
$lang = current_lang();
?>
<!-- HERO -->
<section id="home" class="hero">
    <div class="hero-grid"></div>
    <div class="hero-content">
        <a href="<?= url('/20-11') ?>" class="td-home-badge" data-aos="fade-down">
            <span class="td-home-badge-icon">🌸</span>
            <span class="td-home-badge-text">
                <?= $lang === 'vi' ? 'Chúc mừng Ngày Nhà giáo Việt Nam 20/11' : "Happy Vietnamese Teachers' Day · 20/11" ?>
            </span>
            <span class="td-home-badge-cta">
                <?= $lang === 'vi' ? 'Tri ân Thầy Cô' : 'Thank our teachers' ?>
                <i class="fas fa-arrow-right"></i>
            </span>
        </a>

        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">
            <span class="title-line" data-i18n="hero.title1">Intelligent System</span>
            <span class="title-line gradient-text" data-i18n="hero.title2">&amp; IoT Laboratory</span>
        </h1>

        <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
            <a href="<?= url('/research') ?>" class="btn btn-primary">
                <span data-i18n="hero.btn1">Explore More</span>
                <i class="fas fa-arrow-right"></i>
            </a>
            <a href="<?= url('/contact') ?>" class="btn btn-outline">
                <span data-i18n="hero.btn2">Join Us</span>
                <i class="fas fa-user-plus"></i>
            </a>
        </div>

        <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
            <div class="stat-item">
                <div class="stat-number" data-target="<?= e(setting('stat_members','30')) ?>">0</div>
                <div class="stat-label" data-i18n="hero.stat1">Members</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-target="<?= e(setting('stat_projects','10')) ?>">0</div>
                <div class="stat-label" data-i18n="hero.stat2">Projects</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-target="<?= e(setting('stat_publications','50')) ?>">0</div>
                <div class="stat-label" data-i18n="hero.stat3">Publications</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-target="<?= e(setting('stat_years','5')) ?>">0</div>
                <div class="stat-label" data-i18n="hero.stat4">Years</div>
            </div>
        </div>
    </div>


</section>

<!-- HIGHLIGHT: Research preview -->
<section class="section section-research">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag" data-i18n="research.tag">Our Research</span>
            <h2 class="section-title" data-i18n="research.title">Research Areas</h2>
            <div class="section-line"></div>
            <p class="section-subtitle" data-i18n="research.subtitle">Exploring the boundaries of technology and innovation</p>
        </div>
        <?php
        // Use the same corrected icons as the full Research page for the
        // three renamed research areas; keep all other database icons unchanged.
        $researchIconOverrides = [
            'slam'       => 'fa-map-location-dot',
            'uwb radar'  => 'fa-satellite-dish',
            '3d printer' => 'fa-cube',
        ];
        ?>
        <div class="research-grid">
            <?php foreach ($research as $i => $r): ?>
            <?php
                $researchTitleEn = strtolower(trim((string)($r['title_en'] ?? '')));
                $researchIcon = $researchIconOverrides[$researchTitleEn] ?? ($r['icon'] ?: 'fa-flask');
            ?>
            <div class="research-card" data-aos="fade-up" data-aos-delay="<?= $i*80 ?>">
                <div class="research-icon"><i class="fas <?= e($researchIcon) ?>"></i></div>
                <h3 class="research-title"><?= e(tr($r,'title')) ?></h3>
                <p class="research-desc"><?= e(tr($r,'desc')) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:30px">
            <a href="<?= url('/research') ?>" class="btn btn-outline">
                <span><?= $lang==='vi' ? 'Xem tất cả' : 'View all' ?></span><i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Featured projects -->
<?php if (!empty($featured)): ?>
<section class="section section-projects">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag" data-i18n="projects.tag">Our Work</span>
            <h2 class="section-title" data-i18n="projects.title">Featured Projects</h2>
            <div class="section-line"></div>
        </div>
        <div class="projects-grid">
            <?php foreach ($featured as $i => $p): ?>
            <div class="project-card" data-aos="fade-up" data-aos-delay="<?= $i*70 ?>">
                <div class="project-image">
                    <img src="<?= e(img_url($p['image'], 'https://via.placeholder.com/800x600/00bcd4/ffffff?text=Project')) ?>" alt="<?= e(tr($p,'title')) ?>">
                    <span class="project-tag"><?= e($p['tag'] ?: 'Project') ?></span>
                </div>
                <div class="project-info">
                    <h3 class="project-title"><?= e(tr($p,'title')) ?></h3>
                    <p class="project-desc"><?= e(tr($p,'desc')) ?></p>
                    <div class="project-meta">
                        <span><i class="far fa-calendar"></i> <?= e($p['year']) ?> • <?= e(tr($p,'status')) ?></span>
                        <a href="<?= url('/projects/'.$p['slug']) ?>">View <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:30px">
            <a href="<?= url('/projects') ?>" class="btn btn-outline">
                <span><?= $lang==='vi' ? 'Tất cả dự án' : 'All projects' ?></span><i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Recent news -->
<?php if (!empty($recent_news)): ?>
<section class="section section-news">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag" data-i18n="news.tag">Latest Updates</span>
            <h2 class="section-title" data-i18n="news.title">News &amp; Activities</h2>
            <div class="section-line"></div>
        </div>
        <div class="news-grid">
            <?php foreach ($recent_news as $i => $n): ?>
            <article class="news-card" data-aos="fade-up" data-aos-delay="<?= $i*70 ?>">
                <div class="news-image">
                    <img src="<?= e(img_url($n['image'], 'https://via.placeholder.com/800x600/1976d2/ffffff?text=News')) ?>" alt="<?= e(tr($n,'title')) ?>">
                </div>
                <div class="news-info">
                    <div class="news-date"><i class="far fa-calendar"></i> <?= e(fmt_date($n['date'])) ?></div>
                    <h3 class="news-title"><a href="<?= url('/news/'.$n['slug']) ?>" style="color:inherit;text-decoration:none"><?= e(tr($n,'title')) ?></a></h3>
                    <p class="news-excerpt"><?= e(tr($n,'excerpt')) ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
