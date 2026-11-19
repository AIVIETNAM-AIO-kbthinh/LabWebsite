<?php /** @var array $grouped */ $lang = current_lang(); ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home / Publications</div>
        <h1 data-i18n="pub.title">Publications</h1>
        <p class="subtitle" data-i18n="pub.subtitle">Our contributions to the scientific community</p>
    </div>
</section>

<section class="section section-publications" style="padding-top:50px">
    <div class="container">
        <?php if (empty($grouped)): ?>
        <div class="empty-state"><i class="fas fa-book"></i><p>No publications yet</p></div>
        <?php else: ?>
            <?php foreach ($grouped as $year => $pubs): ?>
                <div class="pub-year-group" data-aos="fade-up">
                    <h3><i class="fas fa-calendar-alt"></i> <?= e($year) ?></h3>
                    <div class="publications-list">
                        <?php foreach ($pubs as $i => $p): ?>
                        <div class="pub-item" data-aos="fade-up" data-aos-delay="<?= $i*40 ?>">
                            <div class="pub-year"><?= e($p['year']) ?></div>
                            <div class="pub-content">
                                <h4 class="pub-title"><?= e($p['title']) ?></h4>
                                <p class="pub-authors"><?= e($p['authors']) ?></p>
                                <p class="pub-venue"><?= e($p['venue']) ?></p>
                                <?php if (!empty($p['link']) && $p['link']!=='#'): ?>
                                    <a href="<?= e($p['link']) ?>" target="_blank" rel="noopener" class="pub-link">View Paper <i class="fas fa-external-link-alt"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
