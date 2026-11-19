<?php /** @var array $items */ $lang = current_lang(); ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">
            <?= $lang === 'vi' ? 'Trang chủ / Tin tức' : 'Home / News' ?>
        </div>
        <h1 data-i18n="news.title">News &amp; Activities</h1>
        <p class="subtitle" data-i18n="news.subtitle">Stay updated with our latest activities</p>
    </div>
</section>

<section class="section section-news" style="padding-top:50px">
    <div class="container">
        <?php if (empty($items)): ?>
        <div class="empty-state">
            <i class="fas fa-newspaper"></i>
            <p><?= $lang === 'vi' ? 'Chưa có tin tức' : 'No news yet' ?></p>
        </div>
        <?php else: ?>
        <div class="news-grid">
            <?php foreach ($items as $i => $n): ?>
            <article class="news-card" data-aos="fade-up" data-aos-delay="<?= $i*60 ?>">
                <div class="news-image">
                    <img src="<?= e(img_url($n['image'], 'https://via.placeholder.com/800x600/1976d2/ffffff?text=News')) ?>" alt="<?= e(tr($n,'title')) ?>">
                </div>
                <div class="news-info">
                    <div class="news-date"><i class="far fa-calendar"></i> <?= e(fmt_date($n['date'])) ?></div>
                    <h3 class="news-title">
                        <a href="<?= url('/news/'.$n['slug']) ?>" style="color:inherit;text-decoration:none"><?= e(tr($n,'title')) ?></a>
                    </h3>
                    <p class="news-excerpt"><?= e(tr($n,'excerpt')) ?></p>
                    <a href="<?= url('/news/'.$n['slug']) ?>" class="pub-link" style="margin-top:10px;display:inline-block">
                        <?= $lang==='vi' ? 'Đọc tiếp' : 'Read more' ?> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
