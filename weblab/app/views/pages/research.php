<?php /** @var array $items */ $lang = current_lang(); ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home / Research</div>
        <h1 data-i18n="research.title">Research Areas</h1>
        <p class="subtitle" data-i18n="research.subtitle">Exploring the boundaries of technology and innovation</p>
    </div>
</section>

<section class="section section-research" style="padding-top:60px">
    <div class="container">
        <?php if (empty($items)): ?>
        <div class="empty-state"><i class="fas fa-flask"></i><p>No research areas yet</p></div>
        <?php else: ?>
        <?php
        // Keep the existing database icon for every research area, except the
        // three renamed areas below whose previous icons no longer match.
        $researchIconOverrides = [
            'slam'       => 'fa-map-location-dot',
            'uwb radar'  => 'fa-satellite-dish',
            '3d printer' => 'fa-cube',
        ];
        ?>
        <div class="research-grid">
            <?php foreach ($items as $i => $r): ?>
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
        <?php endif; ?>
    </div>
</section>
