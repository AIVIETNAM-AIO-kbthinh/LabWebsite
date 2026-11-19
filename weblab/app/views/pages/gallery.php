<?php /** @var array $images */ ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home / Gallery</div>
        <h1 data-i18n="gallery.title">Lab Moments</h1>
        <p class="subtitle" data-i18n="gallery.subtitle">Capturing memorable moments</p>
    </div>
</section>

<section class="section section-gallery" style="padding-top:50px">
    <div class="container">
        <?php if (empty($images)): ?>
        <div class="empty-state"><i class="fas fa-image"></i><p>No images yet</p></div>
        <?php else: ?>
        <div class="gallery-grid">
            <?php foreach ($images as $i => $g): ?>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="<?= $i*40 ?>">
                <img src="<?= e(img_url($g['image'])) ?>" alt="<?= e($g['caption']) ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
