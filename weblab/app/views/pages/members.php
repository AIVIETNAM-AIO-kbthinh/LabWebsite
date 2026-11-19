<?php /** @var array $members */ $lang = current_lang(); ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs"><?= $lang === 'vi' ? 'Trang chủ / Thành viên' : 'Home / Members' ?></div>
        <h1 data-i18n="members.title">Meet the Team</h1>
        <p class="subtitle" data-i18n="members.subtitle">Brilliant minds working together</p>
    </div>
</section>

<section class="section section-members" style="padding-top:50px">
    <div class="container">
        <div class="members-tabs" data-aos="fade-up">
            <button class="member-tab active" data-filter="all"      data-i18n="members.tab1">All</button>
            <button class="member-tab"        data-filter="leader"   data-i18n="members.tab2">Lab Leaders</button>
            <button class="member-tab"        data-filter="lecturer" data-i18n="members.tab3">Lecturers</button>
            <button class="member-tab"        data-filter="student"  data-i18n="members.tab4">Students</button>
            <button class="member-tab"        data-filter="alumni"   data-i18n="members.tab5">Alumni</button>
        </div>

        <?php if (empty($members)): ?>
        <div class="empty-state"><i class="fas fa-users"></i><p><?= $lang === 'vi' ? 'Chưa có thành viên' : 'No members yet' ?></p></div>
        <?php else: ?>
        <div class="members-grid">
            <?php foreach ($members as $i => $m): ?>
            <div class="member-card" data-role="<?= e($m['role']) ?>" data-aos="fade-up" data-aos-delay="<?= $i*60 ?>">
                <div class="member-image">
                    <img src="<?= e(img_url($m['image'], 'https://i.pravatar.cc/400')) ?>" alt="<?= e($m['name']) ?>" onerror="this.src='https://i.pravatar.cc/400'">
                    <span class="member-role"><?= e(tr($m,'title') ?: ucfirst($m['role'])) ?></span>
                </div>
                <div class="member-info">
                    <h3 class="member-name"><?= e($m['name']) ?></h3>
                    <p class="member-title"><?= e(tr($m,'title')) ?></p>
                    <p class="member-desc"><?= e(tr($m,'desc')) ?></p>
                    <div class="member-card-footer">
                        <div class="member-social">
                            <?php if ($m['email']):    ?><a href="mailto:<?= e($m['email']) ?>" title="Email"><i class="fas fa-envelope"></i></a><?php endif; ?>
                            <?php if ($m['scholar'] && $m['scholar']!=='#'): ?><a href="<?= e($m['scholar']) ?>" target="_blank" rel="noopener" title="Scholar"><i class="fas fa-graduation-cap"></i></a><?php endif; ?>
                            <?php if ($m['linkedin'] && $m['linkedin']!=='#'):?><a href="<?= e($m['linkedin']) ?>" target="_blank" rel="noopener" title="LinkedIn"><i class="fab fa-linkedin"></i></a><?php endif; ?>
                            <?php if (!empty($m['github']) && $m['github']!=='#'): ?><a href="<?= e($m['github']) ?>" target="_blank" rel="noopener" title="GitHub"><i class="fab fa-github"></i></a><?php endif; ?>
                        </div>
                        <a class="member-view-link" href="<?= url('/members/' . rawurlencode($m['slug'])) ?>">
                            <?= $lang === 'vi' ? 'Xem' : 'View' ?> <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
