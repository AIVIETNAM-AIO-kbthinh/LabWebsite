<?php /** @var array $row */ ?>
<div class="adm-section-toolbar">
    <div>
        <h2 style="margin:0;font-family:'Nunito Sans',sans-serif;color:#fff;font-size:1.2rem;letter-spacing:1px">Admin Account</h2>
        <p style="margin:8px 0 0;color:var(--adm-text-soft)">Change the username, email and password used to sign in to the admin panel.</p>
    </div>
</div>

<form class="adm-card" method="post" action="<?= url('/admin/account') ?>" autocomplete="off">
    <?= csrf_field() ?>
    <div class="adm-form-grid">
        <div class="adm-field">
            <label>Username</label>
            <input type="text" name="username" value="<?= e($row['username']) ?>" required>
            <small>Use letters, numbers, dot, underscore or dash. Minimum 3 characters.</small>
        </div>
        <div class="adm-field">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($row['email']) ?>" required>
        </div>
        <div class="adm-field full">
            <label>Full name</label>
            <input type="text" name="full_name" value="<?= e($row['full_name']) ?>">
        </div>
        <div class="adm-field full">
            <label>Current password</label>
            <input type="password" name="current_password" autocomplete="current-password">
            <small>Only required when you want to change the password.</small>
        </div>
        <div class="adm-field">
            <label>New password</label>
            <input type="password" name="new_password" autocomplete="new-password">
            <small>Leave blank if you do not want to change it. Minimum 8 characters.</small>
        </div>
        <div class="adm-field">
            <label>Confirm new password</label>
            <input type="password" name="confirm_password" autocomplete="new-password">
        </div>
        <div class="adm-field full">
            <label>Account information</label>
            <div style="color:var(--adm-text-soft);line-height:1.7">
                Role: <strong><?= e($row['role']) ?></strong><br>
                Last login: <strong><?= e($row['last_login'] ?: 'Never') ?></strong><br>
                Created at: <strong><?= e($row['created_at']) ?></strong>
            </div>
        </div>
    </div>
    <div class="adm-form-actions">
        <button class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> Save admin account</button>
    </div>
</form>
