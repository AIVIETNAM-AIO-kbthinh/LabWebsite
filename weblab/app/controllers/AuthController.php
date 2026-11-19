<?php
/**
 * AuthController - admin login / logout.
 */
class AuthController
{
    public function loginForm(): void
    {
        if (is_admin()) redirect(url('/admin'));
        view('admin/login', [
            'page_title' => 'Admin Login | ' . SITE_NAME,
        ], 'admin_blank');
    }

    public function login(): void
    {
        if (!csrf_check()) {
            flash_set('error', 'Invalid security token.');
            redirect(url('/admin/login'));
        }
        $u = trim($_POST['username'] ?? '');
        $p = (string)($_POST['password'] ?? '');

        $row = Database::get()->one(
            'SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1',
            [$u, $u]
        );
        if (!$row || !password_verify($p, $row['password'])) {
            flash_set('error', 'Invalid username or password.');
            redirect(url('/admin/login'));
        }
        // Rotate session id
        start_session();
        session_regenerate_id(true);
        $_SESSION['admin_id']   = (int)$row['id'];
        $_SESSION['admin_user'] = $row['username'];
        $_SESSION['admin_role'] = $row['role'];

        Database::get()->update('admins', ['last_login' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $row['id']]);
        redirect(url('/admin'));
    }

    public function logout(): void
    {
        start_session();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        redirect(url('/admin/login'));
    }
}
