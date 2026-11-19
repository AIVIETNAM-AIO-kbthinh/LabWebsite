<?php /** @var array $rows */ ?>
<div class="adm-section-toolbar">
    <h2 style="margin:0;font-family:'Nunito Sans',sans-serif;color:#fff;font-size:1.2rem;letter-spacing:1px">Site Settings</h2>
</div>

<form class="adm-card" method="post" action="<?= url('/admin/settings') ?>">
    <?= csrf_field() ?>
    <div class="adm-form-grid">
        <?php foreach ($rows as $r):
            $isLong = strlen((string)$r['value']) > 80;
        ?>
            <div class="adm-field <?= $isLong?'full':'' ?>">
                <label><?= e($r['key_name']) ?></label>
                <?php if ($isLong): ?>
                    <textarea name="settings[<?= e($r['key_name']) ?>]" rows="3"><?= e($r['value']) ?></textarea>
                <?php else: ?>
                    <input type="text" name="settings[<?= e($r['key_name']) ?>]" value="<?= e($r['value']) ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="adm-form-actions">
        <button class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> Save settings</button>
    </div>
</form>
