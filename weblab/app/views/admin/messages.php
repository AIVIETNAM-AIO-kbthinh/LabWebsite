<?php /** @var array $rows */ ?>
<div class="adm-section-toolbar">
    <h2 style="margin:0;font-family:'Nunito Sans',sans-serif;color:#fff;font-size:1.2rem;letter-spacing:1px">
        Contact Messages <span style="color:var(--adm-text-soft);font-size:.85rem;margin-left:8px">(<?= count($rows) ?>)</span>
    </h2>
</div>

<div class="adm-table-wrap">
<table class="adm-table">
    <thead><tr>
        <th>ID</th><th>From</th><th>Subject</th><th>Message</th><th>Status</th><th>Date</th><th style="text-align:right">Actions</th>
    </tr></thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--adm-text-soft)">No messages yet.</td></tr>
    <?php else: foreach ($rows as $m): ?>
    <tr>
        <td><?= e($m['id']) ?></td>
        <td>
            <strong><?= e($m['name']) ?></strong><br>
            <small><a href="mailto:<?= e($m['email']) ?>" style="color:var(--adm-accent)"><?= e($m['email']) ?></a></small>
        </td>
        <td><?= e($m['subject']) ?></td>
        <td style="max-width:340px;color:var(--adm-text-soft);white-space:pre-wrap"><?= e(mb_strimwidth($m['message'],0,160,'…')) ?></td>
        <td><?= $m['is_read'] ? '<span class="adm-badge on">read</span>' : '<span class="adm-badge off">new</span>' ?></td>
        <td><small><?= e(date('Y-m-d H:i', strtotime($m['created_at']))) ?></small></td>
        <td style="text-align:right">
            <div class="adm-actions" style="justify-content:flex-end">
                <?php if (!$m['is_read']): ?>
                <form method="post" action="<?= url('/admin/messages/'.$m['id'].'/read') ?>" style="display:inline">
                    <?= csrf_field() ?>
                    <button class="adm-btn adm-btn-icon" title="Mark as read"><i class="fas fa-envelope-open"></i></button>
                </form>
                <?php endif; ?>
                <form method="post" action="<?= url('/admin/messages/'.$m['id'].'/delete') ?>" onsubmit="return confirm('Delete this message?');" style="display:inline">
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
