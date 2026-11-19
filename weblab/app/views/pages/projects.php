<?php
/** @var array $projects */
/** @var array $tags */
/** @var array $years */
/** @var string $sel_tag */
/** @var string $sel_year */
$lang = current_lang();
?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home / Projects</div>
        <h1 data-i18n="projects.title">Featured Projects</h1>
        <p class="subtitle" data-i18n="projects.subtitle">Innovation in action</p>
    </div>
</section>

<section class="section section-projects" style="padding-top:50px">
    <div class="container">
        <form class="filter-bar" method="get" data-aos="fade-up">
            <select name="tag" onchange="this.form.submit()">
                <option value=""><?= $lang==='vi' ? 'Tất cả lĩnh vực' : 'All tags' ?></option>
                <?php foreach ($tags as $t): ?>
                    <option value="<?= e($t) ?>" <?= $sel_tag===$t?'selected':'' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="year" onchange="this.form.submit()">
                <option value=""><?= $lang==='vi' ? 'Tất cả năm' : 'All years' ?></option>
                <?php foreach ($years as $y): ?>
                    <option value="<?= e($y) ?>" <?= (string)$sel_year===(string)$y?'selected':'' ?>><?= e($y) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($sel_tag || $sel_year): ?>
                <a class="reset" href="<?= url('/projects') ?>"><i class="fas fa-times"></i> <?= $lang==='vi' ? 'Xoá bộ lọc' : 'Reset' ?></a>
            <?php endif; ?>
        </form>

        <?php if (empty($projects)): ?>
        <div class="empty-state"><i class="fas fa-folder-open"></i><p>No projects match your filter.</p></div>
        <?php else: ?>
        <div class="projects-grid">
            <?php foreach ($projects as $i => $p): ?>
            <div class="project-card" data-aos="fade-up" data-aos-delay="<?= $i*60 ?>">
                <div class="project-image">
                    <img src="<?= e(img_url($p['image'], 'https://via.placeholder.com/800x600/00bcd4/ffffff?text=Project')) ?>" alt="<?= e(tr($p,'title')) ?>">
                    <span class="project-tag"><?= e($p['tag'] ?: 'Project') ?></span>
                </div>
                <div class="project-info">
                    <h3 class="project-title"><?= e(tr($p,'title')) ?></h3>
                    <p class="project-desc"><?= e(tr($p,'desc')) ?></p>
                    <div class="project-meta">
                        <span><i class="far fa-calendar"></i> <?= e($p['year']) ?> • <?= e(tr($p,'status')) ?></span>
                        <a href="<?= url('/projects/'.$p['slug']) ?>"><?= $lang==='vi' ? 'Xem' : 'View' ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
