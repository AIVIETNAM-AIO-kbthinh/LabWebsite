<?php /** @var array $item */ $lang=current_lang(); $n=$item; ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">
            <a style="color:inherit" href="<?= url('/news') ?>">
                <?= $lang === 'vi' ? 'Tin tức' : 'News' ?>
            </a>
            / <?= e(tr($n,'title')) ?>
        </div>
        <h1><?= e(tr($n,'title')) ?></h1>
        <p class="subtitle"><i class="far fa-calendar"></i> <?= e(fmt_date($n['date'])) ?></p>
    </div>
</section>

<section class="section" style="padding-top:30px">
    <div class="container">
        <div class="detail-wrap">
            <?php if (!empty($n['image'])): ?>
                <img class="detail-cover" src="<?= e(img_url($n['image'])) ?>" alt="<?= e(tr($n,'title')) ?>">
            <?php endif; ?>
            <div class="detail-body">
                <?php
                    $body = tr($n,'content');
                    if (!$body) $body = tr($n,'excerpt');
                ?>
                <p><?= nl2br_e($body) ?></p>
            </div>
            <a href="<?= url('/news') ?>" class="back-link"><i class="fas fa-arrow-left"></i> <?= $lang==='vi' ? 'Tất cả tin' : 'All news' ?></a>
        </div>
    </div>
</section>
