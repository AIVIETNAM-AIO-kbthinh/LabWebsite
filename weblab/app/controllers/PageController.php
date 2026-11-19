<?php
/**
 * PageController - public site pages.
 * Each tab now has its own URL and renders a dedicated page.
 */
class PageController
{
    private function db(): Database { return Database::get(); }

    /** GET / */
    public function home(): void
    {
        $db = $this->db();
        view('pages/home', [
            'current'      => 'home',
            'page_title'   => 'ISI Lab - Intelligent System and IoT Laboratory',
            'research'     => $db->all('SELECT * FROM research_areas WHERE is_active=1 ORDER BY sort_order LIMIT 6'),
            'featured'     => $db->all('SELECT * FROM projects WHERE is_active=1 AND is_featured=1 ORDER BY year DESC LIMIT 3'),
            'recent_news'  => $db->all('SELECT * FROM news WHERE is_active=1 ORDER BY date DESC LIMIT 3'),
            'leaders'      => $db->all("SELECT * FROM members WHERE is_active=1 AND role IN ('leader','lecturer') ORDER BY sort_order LIMIT 4"),
        ]);
    }

    /** GET /about */
    public function about(): void
    {
        view('pages/about', [
            'current'    => 'about',
            'page_title' => 'About | ' . SITE_NAME,
        ]);
    }

    /** GET /research */
    public function research(): void
    {
        $rows = $this->db()->all('SELECT * FROM research_areas WHERE is_active=1 ORDER BY sort_order');
        view('pages/research', [
            'current'    => 'research',
            'page_title' => 'Research Areas | ' . SITE_NAME,
            'items'      => $rows,
        ]);
    }

    /** GET /members */
    public function members(): void
    {
        $rows = $this->db()->all('SELECT * FROM members WHERE is_active=1 ORDER BY FIELD(role,"leader","lecturer","student","alumni"), sort_order');
        view('pages/members', [
            'current'    => 'members',
            'page_title' => 'Members | ' . SITE_NAME,
            'members'    => $rows,
        ]);
    }


    /** GET /members/{slug} */
    public function memberDetail(string $slug): void
    {
        $row = $this->db()->one('SELECT * FROM members WHERE slug = ? AND is_active=1', [$slug]);
        if (!$row) { http_response_code(404); view('pages/error_404'); return; }
        view('pages/member_detail', [
            'current'    => 'members',
            'page_title' => $row['name'] . ' | ' . SITE_NAME,
            'member'     => $row,
        ]);
    }

    /** GET /projects */
    public function projects(): void
    {
        $tag = trim($_GET['tag'] ?? '');
        $year = trim($_GET['year'] ?? '');
        $sql = 'SELECT * FROM projects WHERE is_active=1';
        $params = [];
        if ($tag !== '')  { $sql .= ' AND tag = ?';  $params[] = $tag; }
        if ($year !== '') { $sql .= ' AND year = ?'; $params[] = (int)$year; }
        $sql .= ' ORDER BY year DESC, id DESC';
        $rows = $this->db()->all($sql, $params);

        $tags  = $this->db()->all('SELECT DISTINCT tag FROM projects WHERE is_active=1 AND tag IS NOT NULL AND tag <> "" ORDER BY tag');
        $years = $this->db()->all('SELECT DISTINCT year FROM projects WHERE is_active=1 AND year IS NOT NULL ORDER BY year DESC');

        view('pages/projects', [
            'current'    => 'projects',
            'page_title' => 'Projects | ' . SITE_NAME,
            'projects'   => $rows,
            'tags'       => array_column($tags, 'tag'),
            'years'      => array_column($years, 'year'),
            'sel_tag'    => $tag,
            'sel_year'   => $year,
        ]);
    }

    /** GET /projects/{slug} */
    public function projectDetail(string $slug): void
    {
        $row = $this->db()->one('SELECT * FROM projects WHERE slug = ? AND is_active=1', [$slug]);
        if (!$row) { http_response_code(404); view('pages/error_404'); return; }
        view('pages/project_detail', [
            'current'    => 'projects',
            'page_title' => tr($row, 'title') . ' | ' . SITE_NAME,
            'project'    => $row,
        ]);
    }

    /** GET /publications */
    public function publications(): void
    {
        $rows = $this->db()->all('SELECT * FROM publications WHERE is_active=1 ORDER BY year DESC, id DESC');
        $grouped = [];
        foreach ($rows as $p) $grouped[(int)$p['year']][] = $p;
        view('pages/publications', [
            'current'    => 'publications',
            'page_title' => 'Publications | ' . SITE_NAME,
            'grouped'    => $grouped,
        ]);
    }

    /** GET /news */
    public function news(): void
    {
        $rows = $this->db()->all('SELECT * FROM news WHERE is_active=1 ORDER BY date DESC, id DESC');
        view('pages/news', [
            'current'    => 'news',
            'page_title' => 'News | ' . SITE_NAME,
            'items'      => $rows,
        ]);
    }

    /** GET /news/{slug} */
    public function newsDetail(string $slug): void
    {
        $row = $this->db()->one('SELECT * FROM news WHERE slug = ? AND is_active=1', [$slug]);
        if (!$row) { http_response_code(404); view('pages/error_404'); return; }
        view('pages/news_detail', [
            'current'    => 'news',
            'page_title' => tr($row, 'title') . ' | ' . SITE_NAME,
            'item'       => $row,
        ]);
    }

    /** GET /gallery */
    public function gallery(): void
    {
        $rows = $this->db()->all('SELECT * FROM gallery WHERE is_active=1 ORDER BY sort_order, id DESC');
        view('pages/gallery', [
            'current'    => 'gallery',
            'page_title' => 'Gallery | ' . SITE_NAME,
            'images'     => $rows,
        ]);
    }

    /** GET /contact */
    public function contact(): void
    {
        view('pages/contact', [
            'current'    => 'contact',
            'page_title' => 'Contact | ' . SITE_NAME,
        ]);
    }

    /** GET /20-11 - Vietnamese Teachers' Day greeting page */
    public function teachersDay(): void
    {
        $teachers = $this->db()->all(
            "SELECT * FROM members WHERE is_active=1 AND role IN ('leader','lecturer') ORDER BY FIELD(role,'leader','lecturer'), sort_order"
        );

        // Faculty of Engineering & Technology (VNU-IS) roster. Photos live in
        // public/images/faculty/<name-en-slug>.jpg (from the official profiles).
        // People who are also ISI Lab teachers link to their lab profile.
        $labBySlug = array_column($teachers, null, 'slug');
        $photoDir  = dirname(__DIR__, 2) . '/public/images/faculty/';
        $faculty   = require dirname(__DIR__) . '/data/fet_faculty.php';
        foreach ($faculty as &$f) {
            $photo = strtolower(str_replace(' ', '-', $f['name_en'])) . '.jpg';
            $lab   = $labBySlug[$f['lab_slug'] ?? ''] ?? null;
            $f['image']  = is_file($photoDir . $photo) ? url('/public/images/faculty/' . $photo) : '';
            $f['is_lab'] = $lab !== null;
            $f['link']   = $lab ? url('/members/' . $lab['slug']) : $f['profile'];
            // Lab cards keep their DB photo, but fall back to the faculty photo if it fails to load
            if ($lab && $f['image'] !== '') {
                $labBySlug[$lab['slug']]['fallback_image'] = $f['image'];
            }
        }
        unset($f);
        $teachers = array_values($labBySlug);

        // Days left until 20/11 of the current year (0 = today, negative = passed)
        $today    = new DateTimeImmutable('today');
        $target   = new DateTimeImmutable($today->format('Y') . '-11-20');
        $daysLeft = (int)$today->diff($target)->format('%r%a');

        view('pages/teachers_day', [
            'current'    => 'teachers-day',
            'page_title' => (current_lang() === 'vi' ? 'Chúc mừng 20/11' : "Happy Teachers' Day") . ' | ' . SITE_NAME,
            'teachers'   => $teachers,
            'faculty'    => $faculty,
            'days_left'  => $daysLeft,
            'year'       => (int)$today->format('Y'),
        ], 'celebration');
    }

    /** POST /contact */
    public function contactSubmit(): void
    {
        if (!csrf_check()) {
            flash_set('error', 'Invalid security token. Please try again.');
            redirect(url('/contact'));
        }
        $name    = trim($_POST['name']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $subject === '' || $message === '') {
            flash_set('error', 'Please fill all fields correctly.');
            redirect(url('/contact'));
        }
        $this->db()->insert('contact_messages', [
            'name'       => mb_substr($name, 0, 150),
            'email'      => mb_substr($email, 0, 150),
            'subject'    => mb_substr($subject, 0, 220),
            'message'    => mb_substr($message, 0, 5000),
            'ip'         => $_SERVER['REMOTE_ADDR']     ?? null,
            'user_agent' => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);
        flash_set('success', 'Thanks! Your message has been received.');
        redirect(url('/contact#sent'));
    }
}
