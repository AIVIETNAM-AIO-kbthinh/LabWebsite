<?php /** @var array $stats */ /** @var array $latest_msgs */ ?>
<div class="adm-stats">
    <div class="adm-stat"><div class="ico"><i class="fas fa-users"></i></div><div><div class="label">Members</div><div class="value"><?= e($stats['members']) ?></div></div></div>
    <div class="adm-stat"><div class="ico"><i class="fas fa-folder-open"></i></div><div><div class="label">Projects</div><div class="value"><?= e($stats['projects']) ?></div></div></div>
    <div class="adm-stat"><div class="ico"><i class="fas fa-book"></i></div><div><div class="label">Publications</div><div class="value"><?= e($stats['publications']) ?></div></div></div>
    <div class="adm-stat"><div class="ico"><i class="fas fa-newspaper"></i></div><div><div class="label">News</div><div class="value"><?= e($stats['news']) ?></div></div></div>
    <div class="adm-stat"><div class="ico"><i class="fas fa-images"></i></div><div><div class="label">Gallery</div><div class="value"><?= e($stats['gallery']) ?></div></div></div>
    <div class="adm-stat"><div class="ico" style="background:linear-gradient(135deg,#f59e0b,#ef4444)"><i class="fas fa-inbox"></i></div><div><div class="label">Unread Msg</div><div class="value"><?= e($stats['unread_msgs']) ?></div></div></div>
</div>

<div class="adm-card">
    <h3><i class="fas fa-inbox"></i> Latest Contact Messages</h3>
    <?php if (empty($latest_msgs)): ?>
        <p style="color:var(--adm-text-soft)">No messages yet.</p>
    <?php else: ?>
    <div class="adm-table-wrap">
    <table class="adm-table">
        <thead><tr><th>From</th><th>Subject</th><th>Message</th><th>Date</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($latest_msgs as $m): ?>
        <tr>
            <td>
                <strong><?= e($m['name']) ?></strong><br>
                <small style="color:var(--adm-text-soft)"><?= e($m['email']) ?></small>
            </td>
            <td><?= e($m['subject']) ?></td>
            <td style="max-width:300px;color:var(--adm-text-soft)"><?= e(mb_strimwidth($m['message'],0,90,'…')) ?></td>
            <td><small><?= e(date('Y-m-d H:i', strtotime($m['created_at']))) ?></small></td>
            <td><?= $m['is_read'] ? '<span class="adm-badge on">read</span>' : '<span class="adm-badge off">new</span>' ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div style="margin-top:12px;text-align:right">
        <a class="adm-btn" href="<?= url('/admin/messages') ?>">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    <?php endif; ?>
</div>

<div class="adm-card">
    <h3><i class="fas fa-bolt"></i> Quick actions</h3>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="adm-btn adm-btn-primary" href="<?= url('/admin/news/new') ?>"><i class="fas fa-plus"></i> New article</a>
        <a class="adm-btn" href="<?= url('/admin/projects/new') ?>"><i class="fas fa-plus"></i> New project</a>
        <a class="adm-btn" href="<?= url('/admin/members/new') ?>"><i class="fas fa-plus"></i> New member</a>
        <a class="adm-btn" href="<?= url('/admin/publications/new') ?>"><i class="fas fa-plus"></i> New publication</a>
        <a class="adm-btn" href="<?= url('/admin/gallery/new') ?>"><i class="fas fa-plus"></i> Add image</a>
    </div>
</div>
