<?php /** @var array $project */ $lang = current_lang(); $p = $project; ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs"><a style="color:inherit" href="<?= url('/projects') ?>">Projects</a> / <?= e(tr($p,'title')) ?></div>
        <h1><?= e(tr($p,'title')) ?></h1>
    </div>
</section>

<section class="section" style="padding-top:30px">
    <div class="container">
        <div class="detail-wrap">
            <?php if (!empty($p['image'])): ?>
                <img class="detail-cover" src="<?= e(img_url($p['image'])) ?>" alt="<?= e(tr($p,'title')) ?>">
            <?php endif; ?>
            <div class="detail-meta">
                <?php if ($p['tag']): ?><span class="chip"><?= e($p['tag']) ?></span><?php endif; ?>
                <?php if ($p['year']): ?><span><i class="far fa-calendar"></i> <?= e($p['year']) ?></span><?php endif; ?>
                <?php if (tr($p,'status')): ?><span><i class="fas fa-info-circle"></i> <?= e(tr($p,'status')) ?></span><?php endif; ?>
                <?php if (!empty($p['link']) && $p['link']!=='#'): ?>
                    <a class="chip" href="<?= e($p['link']) ?>" target="_blank" rel="noopener">
                        <i class="fas fa-external-link-alt"></i> <?= $lang==='vi' ? 'Liên kết' : 'External' ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="detail-body">
                <p><?= nl2br_e(tr($p,'desc')) ?></p>
            </div>
            <a href="<?= url('/projects') ?>" class="back-link"><i class="fas fa-arrow-left"></i> <?= $lang==='vi' ? 'Quay lại' : 'Back to projects' ?></a>
        </div>
    </div>
</section>
