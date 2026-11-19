<?php /** @var array $member */ $lang = current_lang(); $m = $member; ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">
            <a style="color:inherit" href="<?= url('/members') ?>"><?= $lang === 'vi' ? 'Thành viên' : 'Members' ?></a>
            / <?= e($m['name']) ?>
        </div>
        <h1><?= e($m['name']) ?></h1>
        <?php if (tr($m,'title')): ?>
            <p class="subtitle"><?= e(tr($m,'title')) ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="section member-detail-section" style="padding-top:40px">
    <div class="container">
        <div class="member-detail-grid">
            <div class="member-detail-photo" data-aos="fade-right">
                <img src="<?= e(img_url($m['image'], 'https://i.pravatar.cc/600')) ?>" alt="<?= e($m['name']) ?>" onerror="this.src='https://i.pravatar.cc/600'">
                <span class="member-role"><?= e(tr($m,'title') ?: ucfirst($m['role'])) ?></span>
            </div>

            <div class="member-detail-content" data-aos="fade-left">
                <?php if (tr($m,'title')): ?>
                    <p class="member-detail-title"><?= e(tr($m,'title')) ?></p>
                <?php endif; ?>

                <?php if (tr($m,'desc')): ?>
                    <div class="member-detail-bio"><?= nl2br_e(tr($m,'desc')) ?></div>
                <?php endif; ?>

                <div class="member-detail-links">
                    <?php if (!empty($m['email'])): ?>
                        <a href="mailto:<?= e($m['email']) ?>"><i class="fas fa-envelope"></i><span><?= e($m['email']) ?></span></a>
                    <?php endif; ?>
                    <?php if (!empty($m['scholar']) && $m['scholar'] !== '#'): ?>
                        <a href="<?= e($m['scholar']) ?>" target="_blank" rel="noopener"><i class="fas fa-graduation-cap"></i><span>Google Scholar</span></a>
                    <?php endif; ?>
                    <?php if (!empty($m['linkedin']) && $m['linkedin'] !== '#'): ?>
                        <a href="<?= e($m['linkedin']) ?>" target="_blank" rel="noopener"><i class="fab fa-linkedin"></i><span>LinkedIn</span></a>
                    <?php endif; ?>
                    <?php if (!empty($m['github']) && $m['github'] !== '#'): ?>
                        <a href="<?= e($m['github']) ?>" target="_blank" rel="noopener"><i class="fab fa-github"></i><span>GitHub</span></a>
                    <?php endif; ?>
                </div>

                <a href="<?= url('/members') ?>" class="back-link"><i class="fas fa-arrow-left"></i> <?= $lang === 'vi' ? 'Quay lại danh sách thành viên' : 'Back to members' ?></a>
            </div>
        </div>
    </div>
</section>
