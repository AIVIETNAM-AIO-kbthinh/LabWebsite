<div class="login-card">
    <div class="brand admin-brand-mark login-brand-mark">
        <img src="<?= url('/public/assets/logo-dark.png') ?>" alt="ISI Lab" class="admin-brand-logo-img">
    </div>
    <h1>Sign in</h1>
    <p class="hint">
        Welcome back. Please log in with your administrator account.
    </p>
    <form method="post" action="<?= url('/admin/login') ?>">
        <?= csrf_field() ?>
        <div class="adm-field" style="margin-bottom:14px">
            <label>Username or Email</label>
            <input type="text" name="username" autofocus required autocomplete="username">
        </div>
        <div class="adm-field" style="margin-bottom:18px">
            <label>Password</label>
            <input type="password" name="password" required autocomplete="current-password">
        </div>
        <button class="adm-btn adm-btn-primary"><i class="fas fa-arrow-right-to-bracket"></i> Login</button>
    </form>
</div>
