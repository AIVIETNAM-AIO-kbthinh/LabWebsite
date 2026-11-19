<?php
/** @var array $cfg */
/** @var array $rows */
/** @var string $section */
?>
<div class="adm-section-toolbar">
    <h2 style="margin:0;font-family:'Nunito Sans',sans-serif;color:#fff;font-size:1.2rem;letter-spacing:1px">
        <?= e($cfg['label']) ?>s
        <span style="color:var(--adm-text-soft);font-size:.85rem;margin-left:8px">(<?= count($rows) ?>)</span>
    </h2>
    <a class="adm-btn adm-btn-primary" href="<?= url('/admin/'.$section.'/new') ?>">
        <i class="fas fa-plus"></i> New <?= e($cfg['label']) ?>
    </a>
</div>

<div class="adm-table-wrap">
<table class="adm-table">
    <thead>
        <tr>
            <th>ID</th>
            <?php foreach ($cfg['list_cols'] as $col): ?>
                <th><?= e($col[1]) ?></th>
            <?php endforeach; ?>
            <th style="width:160px;text-align:right">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr><td colspan="<?= count($cfg['list_cols'])+2 ?>" style="text-align:center;padding:30px;color:var(--adm-text-soft)">
            No items yet. <a href="<?= url('/admin/'.$section.'/new') ?>" style="color:var(--adm-accent)">Create the first one →</a>
        </td></tr>
    <?php else: foreach ($rows as $r): ?>
        <tr>
            <td><?= e($r['id']) ?></td>
            <?php foreach ($cfg['list_cols'] as $col):
                $key = $col[0]; $type = $col[2] ?? 'text'; $v = $r[$key] ?? '';
            ?>
                <td>
                    <?php if ($type === 'img'): ?>
                        <?php if ($v): ?><img class="thumb" src="<?= e(img_url($v)) ?>" alt=""><?php else: ?><span style="color:var(--adm-text-soft)">—</span><?php endif; ?>
                    <?php elseif ($type === 'bool'): ?>
                        <?= $v ? '<span class="adm-badge on">YES</span>' : '<span class="adm-badge off">NO</span>' ?>
                    <?php elseif ($type === 'badge'): ?>
                        <?php if ($v): ?><span class="adm-badge"><?= e($v) ?></span><?php endif; ?>
                    <?php elseif ($type === 'icon'): ?>
                        <i class="fas <?= e($v ?: 'fa-question') ?>" style="color:var(--adm-accent);font-size:18px"></i>
                    <?php else: ?>
                        <?= e(mb_strimwidth((string)$v, 0, 60, '…')) ?>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
            <td style="text-align:right">
                <div class="adm-actions" style="justify-content:flex-end">
                    <a class="adm-btn adm-btn-icon" href="<?= url('/admin/'.$section.'/'.$r['id'].'/edit') ?>" title="Edit"><i class="fas fa-pen"></i></a>
                    <form method="post" action="<?= url('/admin/'.$section.'/'.$r['id'].'/delete') ?>" onsubmit="return confirm('Delete this item?');" style="display:inline">
                        <?= csrf_field() ?>
                        <button class="adm-btn adm-btn-icon adm-btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
