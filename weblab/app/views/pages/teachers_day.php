<?php
/**
 * Vietnamese Teachers' Day (20/11) greeting page.
 *
 * @var array $teachers  leaders + lecturers from `members`
 * @var int   $days_left days until 20/11 this year (0 = today, <0 = passed)
 * @var int   $year
 */
$lang = current_lang();
$t = static fn(string $vi, string $en): string => $lang === 'vi' ? $vi : $en;

// Ngày Nhà giáo Việt Nam được tổ chức lần đầu ngày 20/11/1982.
$anniversary = $year - 1982;

$initials = static function (string $name): string {
    $name  = preg_replace('~^(dr\.|ts\.|ths\.|pgs\.|gs\.)\s*~iu', '', trim($name));
    $parts = preg_split('~\s+~u', $name) ?: [];
    $first = mb_substr($parts[0] ?? '', 0, 1);
    $last  = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';
    return mb_strtoupper($first . $last);
};
?>
<main class="td-main">

    <!-- ================= HERO ================= -->
    <section class="td-hero">
        <div class="td-hero-glow" aria-hidden="true"></div>

        <p class="td-eyebrow td-reveal">
            <span class="td-dot"></span>
            ISI Lab &middot; <?= $t("Kỷ niệm {$anniversary} năm Ngày Nhà giáo Việt Nam", "{$anniversary}th Vietnamese Teachers' Day") ?>
        </p>

        <div class="td-date td-reveal" aria-hidden="true">
            <span>20</span><em>/</em><span>11</span>
        </div>

        <h1 class="td-title td-reveal">
            <span class="td-title-small"><?= $t('Chúc mừng Ngày Nhà giáo Việt Nam', "Happy Vietnamese Teachers' Day") ?></span>
            <span class="td-title-big"><?= $t('Tri ân Thầy Cô', 'Thank you, Teachers') ?></span>
        </h1>

        <p class="td-lead td-reveal">
            <?= $t(
                'Gửi đến những người đã thắp lửa đam mê khoa học, dẫn dắt chúng em qua từng dòng code, từng thí nghiệm và từng ý tưởng.',
                'To those who lit our passion for science and guided us through every line of code, every experiment and every idea.'
            ) ?>
        </p>

        <?php if ($days_left > 0 && $days_left <= 30): ?>
            <p class="td-countdown td-reveal">
                <i class="fas fa-hourglass-half"></i>
                <?= $t("Còn <strong>{$days_left}</strong> ngày nữa là đến 20/11", "<strong>{$days_left}</strong> day" . ($days_left > 1 ? 's' : '') . " until 20 November") ?>
            </p>
        <?php elseif ($days_left === 0): ?>
            <p class="td-countdown td-countdown-today td-reveal">
                <i class="fas fa-star"></i>
                <?= $t('Hôm nay là Ngày Nhà giáo Việt Nam!', "Today is Vietnamese Teachers' Day!") ?>
            </p>
        <?php endif; ?>

        <div class="td-hero-actions td-reveal">
            <a href="#letter" class="td-btn td-btn-primary">
                <i class="fas fa-envelope-open-text"></i>
                <span><?= $t('Mở thư tri ân', 'Open the letter') ?></span>
            </a>
            <a href="<?= url('/') ?>" class="td-btn td-btn-ghost">
                <i class="fas fa-arrow-left"></i>
                <span><?= $t('Về trang chủ', 'Back to home') ?></span>
            </a>
        </div>

        <a href="#letter" class="td-scroll" aria-label="<?= $t('Cuộn xuống', 'Scroll down') ?>">
            <i class="fas fa-chevron-down"></i>
        </a>
    </section>

    <!-- ================= LETTER ================= -->
    <section class="td-section" id="letter">
        <header class="td-section-head td-reveal">
            <span class="td-kicker"><?= $t('Thư gửi Thầy Cô', 'A letter to our teachers') ?></span>
            <h2 class="td-h2"><?= $t('Đôi lời từ ISI Lab', 'A few words from ISI Lab') ?></h2>
        </header>

        <div class="td-envelope-wrap td-reveal">
            <button class="td-envelope" id="tdEnvelope" type="button" aria-expanded="false" aria-controls="tdLetter">
                <span class="td-env-back" aria-hidden="true"></span>
                <span class="td-env-flap" aria-hidden="true"></span>
                <span class="td-env-front" aria-hidden="true"></span>
                <span class="td-env-seal" aria-hidden="true"><i class="fas fa-heart"></i></span>
                <span class="td-env-hint"><?= $t('Nhấn để mở thư', 'Tap to open') ?></span>
            </button>

            <article class="td-letter" id="tdLetter" aria-hidden="true">
                <p class="td-letter-greet"><?= $t('Kính gửi các Thầy, các Cô,', 'Dear Teachers,') ?></p>
                <?php if ($lang === 'vi'): ?>
                    <p>Nhân dịp kỷ niệm <?= $anniversary ?> năm Ngày Nhà giáo Việt Nam (20/11/1982 – 20/11/<?= $year ?>), tập thể thành viên ISI Lab xin gửi đến các Thầy Cô lời tri ân sâu sắc nhất.</p>
                    <p>Cảm ơn Thầy Cô đã không chỉ truyền cho chúng em kiến thức về trí tuệ nhân tạo, IoT hay robot, mà còn dạy chúng em cách đặt câu hỏi, cách kiên nhẫn trước những thí nghiệm chưa thành công, và cách giữ ngọn lửa đam mê với khoa học.</p>
                    <p>Mỗi dòng code chạy được, mỗi bài báo được chấp nhận, mỗi sản phẩm hoàn thành đều mang dấu ấn của sự tận tâm từ Thầy Cô.</p>
                    <p>Kính chúc Thầy Cô luôn mạnh khỏe, hạnh phúc, và mãi là ngọn hải đăng dẫn lối cho nhiều thế hệ sinh viên.</p>
                <?php else: ?>
                    <p>On the <?= $anniversary ?>th anniversary of Vietnamese Teachers' Day (20/11/1982 – 20/11/<?= $year ?>), all members of ISI Lab would like to send you our deepest gratitude.</p>
                    <p>Thank you not only for teaching us about artificial intelligence, IoT and robotics, but also for showing us how to ask the right questions, how to stay patient through failed experiments, and how to keep our passion for science alive.</p>
                    <p>Every line of working code, every accepted paper and every finished product carries the mark of your dedication.</p>
                    <p>We wish you good health and happiness, and may you always be the lighthouse guiding generations of students.</p>
                <?php endif; ?>
                <p class="td-letter-sign">
                    <span><?= $t('Kính thư,', 'With gratitude,') ?></span>
                    <span class="td-signature"><?= $t('Các thành viên ISI Lab', 'The ISI Lab members') ?></span>
                </p>
            </article>
        </div>
    </section>

    <!-- ================= TEACHERS ================= -->
    <?php if (!empty($teachers)): ?>
    <section class="td-section td-section-tint">
        <header class="td-section-head td-reveal">
            <span class="td-kicker"><?= $t('Những người dẫn đường', 'Our mentors') ?></span>
            <h2 class="td-h2"><?= $t('Thầy Cô của ISI Lab', 'The teachers of ISI Lab') ?></h2>
            <p class="td-sub"><?= $t('Gửi một bông hoa tới Thầy Cô bằng cách nhấn nút bên dưới mỗi thẻ.', 'Send a flower to each teacher with the button on their card.') ?></p>
        </header>

        <div class="td-teachers">
            <?php foreach ($teachers as $i => $m):
                $fallback = $m['fallback_image'] ?? '';
                $img      = !empty($m['image']) ? img_url($m['image']) : $fallback;
            ?>
            <article class="td-teacher td-reveal" style="--d: <?= $i * 80 ?>ms">
                <a class="td-avatar" href="<?= url('/members/' . $m['slug']) ?>">
                    <?php if ($img !== ''): ?>
                        <img src="<?= e($img) ?>" alt="<?= e($m['name']) ?>" loading="lazy"
                             data-fallback="<?= e($fallback !== $img ? $fallback : '') ?>"
                             data-initials="<?= e($initials((string)$m['name'])) ?>">
                    <?php else: ?>
                        <span><?= e($initials((string)$m['name'])) ?></span>
                    <?php endif; ?>
                </a>
                <h3 class="td-teacher-name"><?= e($m['name']) ?></h3>
                <p class="td-teacher-role"><?= e(tr($m, 'title')) ?></p>
                <button class="td-flower-btn" type="button" data-flower>
                    <i class="fas fa-seedling"></i>
                    <span><?= $t('Tặng hoa', 'Send a flower') ?></span>
                </button>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ================= FACULTY OF ENGINEERING & TECHNOLOGY ================= -->
    <?php if (!empty($faculty)): ?>
    <section class="td-section" id="faculty">
        <header class="td-section-head td-reveal">
            <span class="td-kicker"><?= $t('Trường Quốc tế – ĐHQGHN', 'VNU International School') ?></span>
            <h2 class="td-h2"><?= $t('Thầy Cô Khoa Kỹ thuật & Công nghệ', 'Faculty of Engineering & Technology') ?></h2>
            <p class="td-sub">
                <?= $t(
                    'Tri ân ' . count($faculty) . ' Thầy Cô của Khoa, những người đã đồng hành cùng sinh viên trên mỗi giảng đường và phòng thí nghiệm.',
                    'With gratitude to the ' . count($faculty) . ' teachers and staff of the Faculty, who walk alongside students in every lecture hall and lab.'
                ) ?>
            </p>
            <button type="button" class="td-btn td-btn-ghost td-flower-all" id="tdFlowerAll">
                <i class="fas fa-fan"></i>
                <span><?= $t('Tặng hoa tất cả Thầy Cô', 'Send flowers to everyone') ?></span>
            </button>
        </header>

        <div class="td-teachers td-teachers-sm">
            <?php foreach ($faculty as $i => $f):
                $fname  = $lang === 'vi' ? $f['name_vi'] : $f['name_en'];
                $degree = $lang === 'vi' ? $f['degree_vi'] : $f['degree_en'];
                $role   = $lang === 'vi' ? $f['role_vi'] : $f['role_en'];
                $ext    = !$f['is_lab'];
            ?>
            <article class="td-teacher td-reveal" style="--d: <?= ($i % 4) * 80 ?>ms">
                <?php if ($f['is_lab']): ?>
                    <span class="td-badge">ISI Lab</span>
                <?php endif; ?>
                <a class="td-avatar" href="<?= e($f['link']) ?>"<?= $ext ? ' target="_blank" rel="noopener"' : '' ?>
                   title="<?= e($t('Xem hồ sơ', 'View profile')) ?>">
                    <?php if (!empty($f['image'])): ?>
                        <img src="<?= e($f['image']) ?>" alt="<?= e($fname) ?>" loading="lazy"
                             width="320" height="320" data-initials="<?= e($initials($f['name_vi'])) ?>">
                    <?php else: ?>
                        <span><?= e($initials($f['name_vi'])) ?></span>
                    <?php endif; ?>
                </a>
                <p class="td-teacher-degree"><?= e($degree) ?></p>
                <h3 class="td-teacher-name"><?= e($fname) ?></h3>
                <p class="td-teacher-role"><?= e($role) ?></p>
                <button class="td-flower-btn" type="button" data-flower>
                    <i class="fas fa-seedling"></i>
                    <span><?= $t('Tặng hoa', 'Send a flower') ?></span>
                </button>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ================= PROVERBS ================= -->
    <section class="td-section td-section-tint">
        <header class="td-section-head td-reveal">
            <span class="td-kicker"><?= $t('Tôn sư trọng đạo', 'Respect for teachers') ?></span>
            <h2 class="td-h2"><?= $t('Lời xưa còn mãi', 'Timeless sayings') ?></h2>
        </header>
        <div class="td-quotes">
            <blockquote class="td-quote td-reveal" style="--d: 0ms">
                <p>“Không thầy đố mày làm nên.”</p>
                <?php if ($lang !== 'vi'): ?><footer>Without a teacher, you can achieve nothing.</footer><?php endif; ?>
            </blockquote>
            <blockquote class="td-quote td-reveal" style="--d: 90ms">
                <p>“Một chữ cũng là thầy, nửa chữ cũng là thầy.”</p>
                <?php if ($lang !== 'vi'): ?><footer>Whoever teaches you even half a word is your teacher.</footer><?php endif; ?>
            </blockquote>
            <blockquote class="td-quote td-reveal" style="--d: 180ms">
                <p>“Muốn sang thì bắc cầu kiều,<br>muốn con hay chữ thì yêu lấy thầy.”</p>
                <?php if ($lang !== 'vi'): ?><footer>To cross the river, build a bridge; to gain knowledge, cherish your teacher.</footer><?php endif; ?>
            </blockquote>
        </div>
    </section>

    <!-- ================= CTA / BACK HOME ================= -->
    <section class="td-cta td-reveal">
        <p class="td-cta-script"><?= $t('Cảm ơn Thầy Cô!', 'Thank you, Teachers!') ?></p>
        <p class="td-cta-text">
            <?= $t('Hành trình nghiên cứu của ISI Lab vẫn đang tiếp tục, cùng sự dẫn dắt của Thầy Cô.', "ISI Lab's research journey continues, guided by our teachers.") ?>
        </p>
        <div class="td-hero-actions">
            <a href="<?= url('/') ?>" class="td-btn td-btn-primary">
                <i class="fas fa-house"></i>
                <span><?= $t('Quay về trang chủ', 'Back to home page') ?></span>
            </a>
            <button type="button" class="td-btn td-btn-ghost" id="tdShower">
                <i class="fas fa-fan"></i>
                <span><?= $t('Thả hoa', 'Shower of petals') ?></span>
            </button>
        </div>
        <p class="td-foot">&copy; <?= $year ?> ISI Lab &middot; Intelligent System &amp; IoT Laboratory</p>
    </section>
</main>
