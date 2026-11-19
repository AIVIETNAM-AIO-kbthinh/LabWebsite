<?php
/** @var array  $cfg */
/** @var array|null $row */
/** @var string $section */
$isEdit = !empty($row);
$action = $isEdit ? url('/admin/'.$section.'/'.$row['id'].'/edit')
                  : url('/admin/'.$section.'/new');
?>
<div class="adm-section-toolbar">
    <h2 style="margin:0;font-family:'Nunito Sans',sans-serif;color:#fff;font-size:1.2rem;letter-spacing:1px">
        <?= $isEdit ? 'Edit' : 'New' ?> <?= e($cfg['label']) ?>
    </h2>
    <a class="adm-btn" href="<?= url('/admin/'.$section) ?>"><i class="fas fa-arrow-left"></i> Back to list</a>
</div>

<form class="adm-card" method="post" action="<?= e($action) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="adm-form-grid">
        <?php foreach ($cfg['fields'] as $f):
            $name = $f['name'];
            $val  = $row[$name] ?? '';
            $type = $f['type'] ?? 'text';
            $full = in_array($type, ['textarea','image'], true);
        ?>
            <div class="adm-field <?= $full?'full':'' ?>">
                <?php if ($type === 'checkbox'): ?>
                    <div class="adm-field-check">
                        <input type="checkbox" id="f-<?= e($name) ?>" name="<?= e($name) ?>" value="1" <?= $val ? 'checked':'' ?>>
                        <label for="f-<?= e($name) ?>"><?= e($f['label']) ?></label>
                    </div>
                <?php else: ?>
                    <label for="f-<?= e($name) ?>"><?= e($f['label']) ?> <?= !empty($f['required'])?'<span style="color:var(--adm-danger)">*</span>':'' ?></label>
                    <?php if ($type === 'textarea'): ?>
                        <textarea id="f-<?= e($name) ?>" name="<?= e($name) ?>" rows="<?= (int)($f['rows'] ?? 4) ?>" <?= !empty($f['required'])?'required':'' ?>><?= e($val) ?></textarea>
                    <?php elseif ($type === 'select'): ?>
                        <select id="f-<?= e($name) ?>" name="<?= e($name) ?>" <?= !empty($f['required'])?'required':'' ?>>
                            <?php foreach ($f['options'] as $optV => $optL): ?>
                                <option value="<?= e($optV) ?>" <?= (string)$val===(string)$optV?'selected':'' ?>><?= e($optL) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($type === 'image'): ?>
                        <div class="adm-upload-box">
                            <?php if ($val): ?>
                                <div style="margin-bottom:12px;display:flex;gap:14px;align-items:center">
                                    <img id="preview-<?= e($name) ?>" src="<?= e(img_url($val)) ?>" style="width:110px;height:110px;object-fit:cover;border-radius:14px;border:1px solid var(--adm-border);background:rgba(255,255,255,.04)">
                                    <div style="color:var(--adm-text-soft);font-size:13px;word-break:break-all;line-height:1.5">
                                        <strong style="color:var(--adm-text)">Current image</strong><br>
                                        <?= e($val) ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <img id="preview-<?= e($name) ?>" src="" alt="" style="display:none;width:110px;height:110px;object-fit:cover;border-radius:14px;border:1px solid var(--adm-border);margin-bottom:12px;background:rgba(255,255,255,.04)">
                            <?php endif; ?>

                            <label style="font-size:13px;color:var(--adm-text-soft);margin-bottom:6px;display:block">Image path / URL</label>
                            <input type="text" name="<?= e($name) ?>" value="<?= e($val) ?>" placeholder="Keep current image, paste URL, or upload from computer below">

                            <div class="help" style="margin-top:10px">Upload trực tiếp từ máy tính: chọn JPG, PNG, WEBP, GIF hoặc SVG. Tối đa 8 MB.</div>
                            <input type="file" id="upload-<?= e($name) ?>" name="upload_<?= e($name) ?>" accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" onchange="previewAdminImage(this,'preview-<?= e($name) ?>')">
                        </div>
                    <?php elseif ($type === 'date'): ?>
                        <input type="date"    id="f-<?= e($name) ?>" name="<?= e($name) ?>" value="<?= e($val) ?>" <?= !empty($f['required'])?'required':'' ?>>
                    <?php elseif ($type === 'int' || $type === 'number'): ?>
                        <input type="number"  id="f-<?= e($name) ?>" name="<?= e($name) ?>" value="<?= e($val) ?>" <?= !empty($f['required'])?'required':'' ?>>
                    <?php else: ?>
                        <input type="text"    id="f-<?= e($name) ?>" name="<?= e($name) ?>" value="<?= e($val) ?>" <?= !empty($f['required'])?'required':'' ?>>
                    <?php endif; ?>
                    <?php if (!empty($f['help'])): ?><div class="help"><?= e($f['help']) ?></div><?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="adm-form-actions">
        <button class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> Save</button>
        <a class="adm-btn" href="<?= url('/admin/'.$section) ?>">Cancel</a>
    </div>
</form>

<script>
function previewAdminImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview || !input.files || !input.files[0]) return;
    const file = input.files[0];
    if (!file.type.startsWith('image/')) return;
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
}
</script>
