<?php
/**
 * AdminController - dashboard + CRUD for all content collections.
 *
 * Tables managed (all the same pattern):
 *   research_areas, members, projects, publications, news, gallery,
 *   contact_messages (read-only), settings (key/value form).
 */
class AdminController
{
    public function __construct() { require_admin(); }

    private function db(): Database { return Database::get(); }

    // ============= DASHBOARD =============
    public function dashboard(): void
    {
        $db = $this->db();
        view('admin/dashboard', [
            'page_title' => 'Dashboard | Admin',
            'current'    => 'dashboard',
            'stats'      => [
                'members'      => (int)$db->value('SELECT COUNT(*) FROM members'),
                'projects'     => (int)$db->value('SELECT COUNT(*) FROM projects'),
                'publications' => (int)$db->value('SELECT COUNT(*) FROM publications'),
                'news'         => (int)$db->value('SELECT COUNT(*) FROM news'),
                'gallery'      => (int)$db->value('SELECT COUNT(*) FROM gallery'),
                'unread_msgs'  => (int)$db->value('SELECT COUNT(*) FROM contact_messages WHERE is_read=0'),
            ],
            'latest_msgs' => $db->all('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5'),
        ], 'admin');
    }

    // ============= GENERIC LIST =============
    public function listAny(string $section): void
    {
        $cfg = $this->cfg($section);
        if ($section === 'members') { $this->ensureMembersAdminSchema(); }
        $rows = $this->db()->all("SELECT * FROM {$cfg['table']} ORDER BY {$cfg['order']}");
        view('admin/list', [
            'page_title' => $cfg['label'] . ' | Admin',
            'current'    => $section,
            'section'    => $section,
            'cfg'        => $cfg,
            'rows'       => $rows,
        ], 'admin');
    }

    // ============= FORM (CREATE / EDIT) =============
    public function formAny(string $section, ?string $id = null): void
    {
        $cfg = $this->cfg($section);
        if ($section === 'members') { $this->ensureMembersAdminSchema(); }
        $row = null;
        if ($id !== null) {
            $row = $this->db()->one("SELECT * FROM {$cfg['table']} WHERE id = ?", [(int)$id]);
            if (!$row) { flash_set('error', 'Item not found.'); redirect(url('/admin/' . $section)); }
        }
        view('admin/form', [
            'page_title' => ($id ? 'Edit ' : 'Add ') . $cfg['label'] . ' | Admin',
            'current'    => $section,
            'section'    => $section,
            'cfg'        => $cfg,
            'row'        => $row,
        ], 'admin');
    }

    // ============= SAVE =============
    public function saveAny(string $section, ?string $id = null): void
    {
        if (!csrf_check()) { flash_set('error','Bad CSRF token.'); redirect(url('/admin/'.$section)); }
        $cfg = $this->cfg($section);

        try {
            // Some local databases were imported from an older version, especially the members table.
            // This keeps the Admin form working by adding safe optional columns when they are missing.
            if ($section === 'members') {
                $this->ensureMembersAdminSchema();
            }

            $data = [];
            foreach ($cfg['fields'] as $f) {
                $name = $f['name'];
                $v    = $_POST[$name] ?? null;
                if ($f['type'] === 'checkbox') { $v = isset($_POST[$name]) ? 1 : 0; }
                if ($f['type'] === 'int' || $f['type'] === 'number') { $v = ($v === '' || $v === null) ? null : (int)$v; }
                if (is_string($v))             { $v = trim($v); }
                if ($v === '')                 { $v = null; }
                $data[$name] = $v;
            }

            // File uploads (image fields). The uploaded image path replaces the typed image URL.
            foreach ($cfg['fields'] as $f) {
                if (($f['type'] ?? '') === 'image') {
                    $uploadKey = 'upload_' . $f['name'];
                    $fileInfo = $_FILES[$uploadKey] ?? ($_FILES[$f['name']] ?? null);
                    if (is_array($fileInfo) && !empty($fileInfo['name'])) {
                        $u = $this->handleUpload($fileInfo, $section);
                        if ($u) $data[$f['name']] = $u;
                    }
                }
            }

            // Auto slug
            if (!empty($cfg['auto_slug']) && empty($data['slug'])) {
                $base = $data['title_en'] ?? $data['name'] ?? ('item-' . time());
                $data['slug'] = $this->uniqueSlug($cfg['table'], slugify($base), $id);
            } elseif (!empty($data['slug'])) {
                $data['slug'] = $this->uniqueSlug($cfg['table'], slugify($data['slug']), $id);
            }

            // Members needs stronger protection because some installations have
            // slightly different/older member table definitions. Prepare first, then
            // filter by the real table columns, then add safe defaults for any required
            // database columns that are not present in the form.
            if ($section === 'members') {
                $data = $this->prepareMemberData($data, $id !== null);
            }

            // Safety: only send columns that really exist in the current database table.
            // This prevents HTTP 500 when an older database misses optional admin fields.
            $data = $this->filterDataByTableColumns($cfg['table'], $data);

            if ($section === 'members') {
                $data = $this->fillRequiredColumnDefaults($cfg['table'], $data);
            }

            if ($id) {
                $this->db()->update($cfg['table'], $data, 'id = :_id', ['_id' => (int)$id]);
                flash_set('success', $cfg['label'] . ' updated.');
            } else {
                $this->db()->insert($cfg['table'], $data);
                flash_set('success', $cfg['label'] . ' created.');
            }
        } catch (Throwable $e) {
            error_log('[ISILAB ADMIN SAVE ERROR] ' . $section . ': ' . $e->getMessage());
            $this->writeAdminSaveLog($section, $e);
            $msg = 'Cannot save this item. Please check required fields, image size, and database columns.';
            // For Members only, show the real database error because this is the
            // section most likely to differ between old/imported schemas.
            if ($section === 'members' || (defined('APP_DEBUG') && APP_DEBUG)) {
                $msg .= ' Detail: ' . $e->getMessage();
            }
            flash_set('error', $msg);
            redirect(url('/admin/' . $section . ($id ? '/' . (int)$id . '/edit' : '/new')));
        }

        redirect(url('/admin/' . $section));
    }


    // ============= DELETE =============
    public function deleteAny(string $section, string $id): void
    {
        if (!csrf_check()) { flash_set('error','Bad CSRF token.'); redirect(url('/admin/'.$section)); }
        $cfg = $this->cfg($section);
        $this->db()->delete($cfg['table'], 'id = ?', [(int)$id]);
        flash_set('success', $cfg['label'] . ' deleted.');
        redirect(url('/admin/' . $section));
    }

    // ============= SETTINGS (key/value) =============
    public function settings(): void
    {
        $rows = $this->db()->all('SELECT * FROM settings ORDER BY key_name');
        view('admin/settings', [
            'page_title' => 'Settings | Admin',
            'current'    => 'settings',
            'rows'       => $rows,
        ], 'admin');
    }

    public function settingsSave(): void
    {
        if (!csrf_check()) { flash_set('error','Bad CSRF token.'); redirect(url('/admin/settings')); }
        $vals = $_POST['settings'] ?? [];
        $db = $this->db();
        foreach ($vals as $k => $v) {
            $db->run('INSERT INTO settings (key_name,value) VALUES (?,?) ON DUPLICATE KEY UPDATE value=VALUES(value)',
                     [(string)$k, (string)$v]);
        }
        flash_set('success', 'Settings saved.');
        redirect(url('/admin/settings'));
    }


    // ============= ADMIN ACCOUNT =============
    public function account(): void
    {
        $adminId = (int)($_SESSION['admin_id'] ?? 0);
        $row = $this->db()->one('SELECT id, username, email, full_name, role, last_login, created_at FROM admins WHERE id = ? LIMIT 1', [$adminId]);
        if (!$row) { flash_set('error', 'Admin account not found.'); redirect(url('/admin/logout')); }
        view('admin/account', [
            'page_title' => 'Admin Account | Admin',
            'current'    => 'account',
            'row'        => $row,
        ], 'admin');
    }

    public function accountSave(): void
    {
        if (!csrf_check()) { flash_set('error','Bad CSRF token.'); redirect(url('/admin/account')); }
        $adminId = (int)($_SESSION['admin_id'] ?? 0);
        $db = $this->db();
        $row = $db->one('SELECT * FROM admins WHERE id = ? LIMIT 1', [$adminId]);
        if (!$row) { flash_set('error', 'Admin account not found.'); redirect(url('/admin/logout')); }

        $username = trim((string)($_POST['username'] ?? ''));
        $email    = trim((string)($_POST['email'] ?? ''));
        $fullName = trim((string)($_POST['full_name'] ?? ''));
        $currentPassword = (string)($_POST['current_password'] ?? '');
        $newPassword     = (string)($_POST['new_password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');

        if ($username === '' || !preg_match('/^[a-zA-Z0-9_.-]{3,64}$/', $username)) {
            flash_set('error', 'Username must be 3-64 characters and may contain letters, numbers, dot, underscore or dash.');
            redirect(url('/admin/account'));
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'Please enter a valid email address.');
            redirect(url('/admin/account'));
        }

        $exists = $db->one('SELECT id FROM admins WHERE (username = ? OR email = ?) AND id <> ? LIMIT 1', [$username, $email, $adminId]);
        if ($exists) {
            flash_set('error', 'Username or email is already used by another admin account.');
            redirect(url('/admin/account'));
        }

        $data = [
            'username'  => $username,
            'email'     => $email,
            'full_name' => $fullName ?: null,
        ];

        if ($newPassword !== '' || $confirmPassword !== '' || $currentPassword !== '') {
            if (!password_verify($currentPassword, $row['password'])) {
                flash_set('error', 'Current password is incorrect.');
                redirect(url('/admin/account'));
            }
            if (strlen($newPassword) < 8) {
                flash_set('error', 'New password must be at least 8 characters.');
                redirect(url('/admin/account'));
            }
            if ($newPassword !== $confirmPassword) {
                flash_set('error', 'New password and confirmation do not match.');
                redirect(url('/admin/account'));
            }
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $db->update('admins', $data, 'id = :id', ['id' => $adminId]);
        $_SESSION['admin_user'] = $username;
        flash_set('success', 'Admin account updated successfully.');
        redirect(url('/admin/account'));
    }

    // ============= CONTACT MESSAGES =============
    public function messages(): void
    {
        $rows = $this->db()->all('SELECT * FROM contact_messages ORDER BY created_at DESC');
        view('admin/messages', [
            'page_title' => 'Messages | Admin',
            'current'    => 'messages',
            'rows'       => $rows,
        ], 'admin');
    }
    public function messageMarkRead(string $id): void
    {
        if (!csrf_check()) { flash_set('error','Bad CSRF token.'); redirect(url('/admin/messages')); }
        $this->db()->update('contact_messages', ['is_read'=>1], 'id=:i', ['i'=>(int)$id]);
        flash_set('success','Marked as read.');
        redirect(url('/admin/messages'));
    }
    public function messageDelete(string $id): void
    {
        if (!csrf_check()) { flash_set('error','Bad CSRF token.'); redirect(url('/admin/messages')); }
        $this->db()->delete('contact_messages','id=?',[(int)$id]);
        flash_set('success','Message deleted.');
        redirect(url('/admin/messages'));
    }

    // ============= INTERNALS =============

    private function writeAdminSaveLog(string $section, Throwable $e): void
    {
        try {
            if (!is_dir(UPLOAD_DIR)) { @mkdir(UPLOAD_DIR, 0775, true); }
            $line = '[' . date('Y-m-d H:i:s') . '] ' . $section . ' | ' . $e->getMessage() . PHP_EOL;
            @file_put_contents(UPLOAD_DIR . '/admin-save-error.log', $line, FILE_APPEND);
        } catch (Throwable $ignore) {
            // Do not let logging break the admin save flow.
        }
    }

    private function uniqueSlug(string $table, string $slug, ?string $excludeId = null): string
    {
        $base = $slug ?: ('item-' . time());
        $try  = $base;
        $i    = 1;
        $db   = $this->db();
        while (true) {
            $exists = $db->one("SELECT id FROM {$table} WHERE slug = ?" . ($excludeId ? ' AND id <> ?' : ''),
                               $excludeId ? [$try, (int)$excludeId] : [$try]);
            if (!$exists) return $try;
            $try = $base . '-' . (++$i);
        }
    }

    private function tableColumns(string $table): array
    {
        // Always read fresh metadata. Admin can ALTER older tables during save, so
        // a static cache could hide newly added columns in the same request.
        $rows = $this->db()->all("SHOW COLUMNS FROM `{$table}`");
        return array_map(fn($r) => $r['Field'], $rows);
    }

    private function refreshTableColumns(string $table): void
    {
        // Kept as a semantic marker after ALTER TABLE. tableColumns() is intentionally
        // uncached, so no reset is required.
        $this->db()->all("SHOW COLUMNS FROM `{$table}`");
    }

    private function filterDataByTableColumns(string $table, array $data): array
    {
        $columns = array_flip($this->tableColumns($table));
        return array_filter($data, fn($k) => isset($columns[$k]), ARRAY_FILTER_USE_KEY);
    }

    private function prepareMemberData(array $data, bool $isEdit): array
    {
        // Do not let an empty form value become a database error. The browser already
        // marks Full Name as required, but this fallback prevents HTTP 500 if the form
        // was submitted by an older browser or after a failed upload.
        if (!isset($data['name']) || trim((string)$data['name']) === '') {
            $data['name'] = 'New Member';
        }

        // Default role if the select value is empty or missing.
        if (empty($data['role'])) {
            $data['role'] = 'student';
        }

        // If the current database uses ENUM for role, make sure posted value is allowed.
        $roleMeta = $this->columnMeta('members', 'role');
        if ($roleMeta && preg_match('/^enum\((.*)\)$/i', (string)$roleMeta['Type'], $m)) {
            preg_match_all("/'((?:[^'\\]|\\.)*)'/", $m[1], $matches);
            $allowed = array_map(fn($v) => stripcslashes($v), $matches[1] ?? []);
            if ($allowed && !in_array($data['role'], $allowed, true)) {
                $data['role'] = in_array('student', $allowed, true) ? 'student' : $allowed[0];
            }
        }

        // sort_order is NOT NULL DEFAULT 0 in the standard schema. Empty form value must be 0, not NULL.
        if (!isset($data['sort_order']) || $data['sort_order'] === null || $data['sort_order'] === '') {
            $data['sort_order'] = 0;
        } else {
            $data['sort_order'] = (int)$data['sort_order'];
        }

        // On new member, keep Active checked by default unless the submitted form explicitly sends 0.
        if (!$isEdit && (!isset($data['is_active']) || $data['is_active'] === null)) {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = !empty($data['is_active']) ? 1 : 0;
        }

        // Empty optional social/profile fields should be stored as NULL.
        foreach (['title_en','title_vi','desc_en','desc_vi','image','email','scholar','linkedin','github'] as $optional) {
            if (array_key_exists($optional, $data) && $data[$optional] === '') {
                $data[$optional] = null;
            }
        }

        return $data;
    }

    private function fillRequiredColumnDefaults(string $table, array $data): array
    {
        $rows = $this->db()->all("SHOW COLUMNS FROM `{$table}`");
        foreach ($rows as $col) {
            $field = (string)$col['Field'];
            $extra = strtolower((string)($col['Extra'] ?? ''));
            $type  = strtolower((string)($col['Type'] ?? ''));

            // Never manually fill auto ids, generated columns, or auto timestamps.
            if ($field === 'id' || str_contains($extra, 'auto_increment') || str_contains($extra, 'generated')) {
                continue;
            }
            if (in_array($field, ['created_at','updated_at','last_login'], true)) {
                continue;
            }
            if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                continue;
            }

            $isRequiredWithoutDefault = (($col['Null'] ?? '') === 'NO') && (($col['Default'] ?? null) === null);
            if (!$isRequiredWithoutDefault) {
                continue;
            }

            if ($field === 'slug') {
                $base = slugify((string)($data['name'] ?? 'member'));
                $data[$field] = $this->uniqueSlug($table, $base);
            } elseif ($field === 'name') {
                $data[$field] = (string)($data['name'] ?? 'New Member');
            } elseif ($field === 'role') {
                $data[$field] = $this->defaultValueForColumnType($type, 'student');
            } else {
                $data[$field] = $this->defaultValueForColumnType($type, '');
            }
        }
        return $data;
    }

    private function defaultValueForColumnType(string $type, $stringDefault = '')
    {
        if (str_starts_with($type, 'enum(')) {
            preg_match_all("/'((?:[^'\\]|\\.)*)'/", $type, $matches);
            $allowed = array_map(fn($v) => stripcslashes($v), $matches[1] ?? []);
            if (in_array($stringDefault, $allowed, true)) return $stringDefault;
            return $allowed[0] ?? $stringDefault;
        }
        if (preg_match('/int|decimal|float|double|real|bit|bool/i', $type)) return 0;
        if (preg_match('/date$/i', $type)) return date('Y-m-d');
        if (preg_match('/time|timestamp|datetime/i', $type)) return date('Y-m-d H:i:s');
        return $stringDefault;
    }

    private function columnMeta(string $table, string $column): ?array
    {
        // MariaDB/MySQL may not allow a prepared placeholder in SHOW COLUMNS ... LIKE
        // when native prepares are enabled. Read the table metadata normally and match
        // the column in PHP instead. This fixes Members → Add New failing with:
        // SQLSTATE[42000] 1064 ... near '?' at line 1.
        foreach ($this->db()->all("SHOW COLUMNS FROM `{$table}`") as $row) {
            if (isset($row['Field']) && (string)$row['Field'] === $column) {
                return $row;
            }
        }
        return null;
    }

    private function ensureMembersAdminSchema(): void
    {
        $columns = array_flip($this->tableColumns('members'));
        $definitions = [
            // Add missing standard admin columns. Defaults are intentionally safe so
            // ALTER TABLE also succeeds on databases that already contain member rows.
            'slug'       => "ADD COLUMN `slug` VARCHAR(160) NOT NULL DEFAULT '' AFTER `id`",
            'name'       => "ADD COLUMN `name` VARCHAR(160) NOT NULL DEFAULT '' AFTER `slug`",
            'role'       => "ADD COLUMN `role` VARCHAR(40) NOT NULL DEFAULT 'student' AFTER `name`",
            'title_en'   => "ADD COLUMN `title_en` VARCHAR(200) NULL AFTER `role`",
            'title_vi'   => "ADD COLUMN `title_vi` VARCHAR(200) NULL AFTER `title_en`",
            'desc_en'    => "ADD COLUMN `desc_en` TEXT NULL AFTER `title_vi`",
            'desc_vi'    => "ADD COLUMN `desc_vi` TEXT NULL AFTER `desc_en`",
            'image'      => "ADD COLUMN `image` VARCHAR(500) NULL AFTER `desc_vi`",
            'email'      => "ADD COLUMN `email` VARCHAR(150) NULL AFTER `image`",
            'scholar'    => "ADD COLUMN `scholar` VARCHAR(500) NULL AFTER `email`",
            'linkedin'   => "ADD COLUMN `linkedin` VARCHAR(500) NULL AFTER `scholar`",
            'github'     => "ADD COLUMN `github` VARCHAR(500) NULL AFTER `linkedin`",
            'sort_order' => "ADD COLUMN `sort_order` INT NOT NULL DEFAULT 0 AFTER `github`",
            'is_active'  => "ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `sort_order`",
        ];
        foreach ($definitions as $col => $sql) {
            if (!isset($columns[$col])) {
                $this->db()->run("ALTER TABLE `members` {$sql}");
            }
        }

        // Refresh cached columns in this request after ALTER TABLE.
        $this->refreshTableColumns('members');

        // Some older imports have strict NOT NULL columns without defaults.
        // These ALTERs make future inserts stable while preserving existing data.
        $safeAlters = [
            "MODIFY COLUMN `slug` VARCHAR(160) NOT NULL DEFAULT ''",
            "MODIFY COLUMN `name` VARCHAR(160) NOT NULL DEFAULT ''",
            "MODIFY COLUMN `sort_order` INT NOT NULL DEFAULT 0",
            "MODIFY COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1",
        ];
        foreach ($safeAlters as $alter) {
            try { $this->db()->run("ALTER TABLE `members` {$alter}"); } catch (Throwable $e) { /* keep compatible */ }
        }
        try { $this->db()->run("ALTER TABLE `members` MODIFY COLUMN `role` VARCHAR(40) NOT NULL DEFAULT 'student'"); } catch (Throwable $e) { /* enum role is also fine */ }
        $this->refreshTableColumns('members');
    }

    private function handleUpload(array $file, string $section): ?string
    {
        // Admin image upload from local computer.
        // Stored path format in DB: section/file-name.jpg, relative to UPLOAD_DIR.
        $err = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($err === UPLOAD_ERR_NO_FILE) return null;
        if ($err !== UPLOAD_ERR_OK) {
            flash_set('error', 'Upload failed. Please choose another image file.');
            return null;
        }

        $original = (string)($file['name'] ?? 'image');
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowedExt = ['jpg','jpeg','png','gif','webp','svg'];
        if (!in_array($ext, $allowedExt, true)) {
            flash_set('error', 'Only JPG, PNG, GIF, WEBP or SVG images are allowed.');
            return null;
        }

        // 8 MB is enough for website images but still safe for shared hosting.
        if (($file['size'] ?? 0) > 8 * 1024 * 1024) {
            flash_set('error', 'Image is too large. Maximum upload size is 8 MB.');
            return null;
        }

        // Validate MIME/content when possible. SVG is text/xml, so it is handled separately.
        if ($ext !== 'svg') {
            $info = @getimagesize($file['tmp_name']);
            if ($info === false) {
                flash_set('error', 'The uploaded file is not a valid image.');
                return null;
            }
        } else {
            $svg = @file_get_contents($file['tmp_name'], false, null, 0, 2048);
            if ($svg === false || stripos($svg, '<svg') === false) {
                flash_set('error', 'The uploaded SVG file is not valid.');
                return null;
            }
        }

        $dir = UPLOAD_DIR . '/' . $section;
        if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
            flash_set('error', 'Cannot create upload folder. Please check folder permission: uploads/.');
            return null;
        }

        $safeBase = preg_replace('~[^a-zA-Z0-9_-]+~', '-', pathinfo($original, PATHINFO_FILENAME));
        $safeBase = trim($safeBase, '-') ?: 'image';
        $name = $section . '-' . date('Ymd-His') . '-' . $safeBase . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            flash_set('error', 'Cannot save uploaded image. Please check folder permission: uploads/.');
            return null;
        }

        @chmod($dest, 0644);
        flash_set('success', 'Image uploaded successfully.');
        return $section . '/' . $name;
    }

    /**
     * Field definitions used by the generic list / form views.
     * One config -> CRUD page works for every content type.
     */
    private function cfg(string $section): array
    {
        $configs = [
            'research' => [
                'table' => 'research_areas',
                'label' => 'Research Area',
                'order' => 'sort_order,id',
                'auto_slug' => true,
                'list_cols' => [
                    ['icon','Icon','icon'], ['title_en','Title (EN)'], ['title_vi','Title (VI)'], ['sort_order','#'],['is_active','Active','bool']
                ],
                'fields' => [
                    ['name'=>'slug',      'label'=>'Slug',           'type'=>'text', 'help'=>'Auto-generated if left blank'],
                    ['name'=>'icon',      'label'=>'Icon class',     'type'=>'text', 'help'=>'Font-Awesome name e.g. fa-brain'],
                    ['name'=>'title_en',  'label'=>'Title (EN)',     'type'=>'text', 'required'=>true],
                    ['name'=>'title_vi',  'label'=>'Title (VI)',     'type'=>'text'],
                    ['name'=>'desc_en',   'label'=>'Description (EN)','type'=>'textarea'],
                    ['name'=>'desc_vi',   'label'=>'Description (VI)','type'=>'textarea'],
                    ['name'=>'sort_order','label'=>'Sort order',     'type'=>'int'],
                    ['name'=>'is_active', 'label'=>'Active',         'type'=>'checkbox'],
                ],
            ],
            'members' => [
                'table' => 'members',
                'label' => 'Member',
                'order' => 'FIELD(role,"leader","lecturer","student","alumni"),sort_order,id',
                'auto_slug' => true,
                'list_cols' => [
                    ['image','Photo','img'],['name','Name'],['role','Role','badge'],['title_en','Title'],['is_active','Active','bool']
                ],
                'fields' => [
                    ['name'=>'slug',     'label'=>'Slug',         'type'=>'text', 'help'=>'Auto-generated if left blank'],
                    ['name'=>'name',     'label'=>'Full Name',    'type'=>'text', 'required'=>true],
                    ['name'=>'role',     'label'=>'Role',         'type'=>'select','options'=>['leader'=>'Lab Leader','lecturer'=>'Lecturer','student'=>'Student','alumni'=>'Alumni']],
                    ['name'=>'title_en', 'label'=>'Title (EN)',   'type'=>'text'],
                    ['name'=>'title_vi', 'label'=>'Title (VI)',   'type'=>'text'],
                    ['name'=>'desc_en',  'label'=>'Bio (EN)',     'type'=>'textarea'],
                    ['name'=>'desc_vi',  'label'=>'Bio (VI)',     'type'=>'textarea'],
                    ['name'=>'image',    'label'=>'Photo URL',    'type'=>'image'],
                    ['name'=>'email',    'label'=>'Email',        'type'=>'text'],
                    ['name'=>'scholar',  'label'=>'Google Scholar','type'=>'text'],
                    ['name'=>'linkedin', 'label'=>'LinkedIn',     'type'=>'text'],
                    ['name'=>'github',   'label'=>'GitHub',       'type'=>'text'],
                    ['name'=>'sort_order','label'=>'Sort order',  'type'=>'int'],
                    ['name'=>'is_active','label'=>'Active',       'type'=>'checkbox'],
                ],
            ],
            'projects' => [
                'table' => 'projects',
                'label' => 'Project',
                'order' => 'year DESC, id DESC',
                'auto_slug' => true,
                'list_cols' => [
                    ['image','Cover','img'],['title_en','Title (EN)'],['tag','Tag','badge'],['year','Year'],['is_featured','★','bool'],['is_active','Active','bool']
                ],
                'fields' => [
                    ['name'=>'slug',      'label'=>'Slug','type'=>'text','help'=>'Auto-generated if left blank'],
                    ['name'=>'title_en',  'label'=>'Title (EN)','type'=>'text','required'=>true],
                    ['name'=>'title_vi',  'label'=>'Title (VI)','type'=>'text'],
                    ['name'=>'desc_en',   'label'=>'Description (EN)','type'=>'textarea'],
                    ['name'=>'desc_vi',   'label'=>'Description (VI)','type'=>'textarea'],
                    ['name'=>'image',     'label'=>'Cover Image','type'=>'image'],
                    ['name'=>'tag',       'label'=>'Tag','type'=>'text','help'=>'e.g. AI / IoT / UAV / Robotics'],
                    ['name'=>'year',      'label'=>'Year','type'=>'int'],
                    ['name'=>'status_en', 'label'=>'Status (EN)','type'=>'text'],
                    ['name'=>'status_vi', 'label'=>'Status (VI)','type'=>'text'],
                    ['name'=>'link',      'label'=>'External link','type'=>'text'],
                    ['name'=>'is_featured','label'=>'Featured on homepage','type'=>'checkbox'],
                    ['name'=>'is_active', 'label'=>'Active','type'=>'checkbox'],
                ],
            ],
            'publications' => [
                'table' => 'publications',
                'label' => 'Publication',
                'order' => 'year DESC, id DESC',
                'list_cols' => [
                    ['year','Year'],['title','Title'],['authors','Authors'],['venue','Venue'],['is_active','Active','bool']
                ],
                'fields' => [
                    ['name'=>'year',   'label'=>'Year','type'=>'int','required'=>true],
                    ['name'=>'title',  'label'=>'Title','type'=>'text','required'=>true],
                    ['name'=>'authors','label'=>'Authors','type'=>'text'],
                    ['name'=>'venue',  'label'=>'Venue / Journal','type'=>'text'],
                    ['name'=>'link',   'label'=>'Paper URL','type'=>'text'],
                    ['name'=>'bibtex', 'label'=>'BibTeX','type'=>'textarea'],
                    ['name'=>'is_active','label'=>'Active','type'=>'checkbox'],
                ],
            ],
            'news' => [
                'table' => 'news',
                'label' => 'News Article',
                'order' => 'date DESC, id DESC',
                'auto_slug' => true,
                'list_cols' => [
                    ['image','Cover','img'],['title_en','Title (EN)'],['date','Date'],['is_active','Active','bool']
                ],
                'fields' => [
                    ['name'=>'slug',      'label'=>'Slug','type'=>'text','help'=>'Auto-generated if left blank'],
                    ['name'=>'title_en',  'label'=>'Title (EN)','type'=>'text','required'=>true],
                    ['name'=>'title_vi',  'label'=>'Title (VI)','type'=>'text'],
                    ['name'=>'excerpt_en','label'=>'Excerpt (EN)','type'=>'textarea'],
                    ['name'=>'excerpt_vi','label'=>'Excerpt (VI)','type'=>'textarea'],
                    ['name'=>'content_en','label'=>'Full content (EN)','type'=>'textarea','rows'=>10],
                    ['name'=>'content_vi','label'=>'Full content (VI)','type'=>'textarea','rows'=>10],
                    ['name'=>'image',     'label'=>'Cover Image','type'=>'image'],
                    ['name'=>'date',      'label'=>'Date','type'=>'date','required'=>true],
                    ['name'=>'is_active', 'label'=>'Active','type'=>'checkbox'],
                ],
            ],
            'gallery' => [
                'table' => 'gallery',
                'label' => 'Gallery Image',
                'order' => 'sort_order, id DESC',
                'list_cols' => [
                    ['image','Image','img'],['caption','Caption'],['sort_order','#'],['is_active','Active','bool']
                ],
                'fields' => [
                    ['name'=>'image',     'label'=>'Image','type'=>'image','required'=>true],
                    ['name'=>'caption',   'label'=>'Caption','type'=>'text'],
                    ['name'=>'sort_order','label'=>'Sort order','type'=>'int'],
                    ['name'=>'is_active', 'label'=>'Active','type'=>'checkbox'],
                ],
            ],
        ];
        if (!isset($configs[$section])) {
            http_response_code(404);
            die('Unknown admin section: ' . e($section));
        }
        return $configs[$section];
    }
}
