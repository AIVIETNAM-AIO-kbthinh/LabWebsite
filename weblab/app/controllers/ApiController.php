<?php
/**
 * ApiController - lightweight JSON endpoints for live front-end widgets
 * (search, gallery, etc.). Public, read-only.
 */
class ApiController
{
    /** GET /api/gallery */
    public function gallery(): void
    {
        $rows = Database::get()->all('SELECT id,image,caption FROM gallery WHERE is_active=1 ORDER BY sort_order,id');
        json_response(['ok' => true, 'data' => $rows]);
    }

    /** GET /api/stats */
    public function stats(): void
    {
        $db = Database::get();
        json_response(['ok' => true, 'data' => [
            'members'      => (int)$db->value('SELECT COUNT(*) FROM members WHERE is_active=1'),
            'projects'     => (int)$db->value('SELECT COUNT(*) FROM projects WHERE is_active=1'),
            'publications' => (int)$db->value('SELECT COUNT(*) FROM publications WHERE is_active=1'),
            'years'        => (int)setting('stat_years', 15),
        ]]);
    }

    /** GET /api/search?q=... */
    public function search(): void
    {
        $q = trim($_GET['q'] ?? '');
        if ($q === '') { json_response(['ok' => true, 'data' => []]); }
        $like = '%' . $q . '%';
        $db = Database::get();
        $projects = $db->all('SELECT id,slug,title_en,title_vi FROM projects WHERE is_active=1 AND (title_en LIKE ? OR title_vi LIKE ?) LIMIT 5', [$like,$like]);
        $news     = $db->all('SELECT id,slug,title_en,title_vi FROM news WHERE is_active=1 AND (title_en LIKE ? OR title_vi LIKE ?) LIMIT 5', [$like,$like]);
        $members  = $db->all('SELECT id,slug,name FROM members WHERE is_active=1 AND name LIKE ? LIMIT 5', [$like]);
        json_response(['ok'=>true,'data'=>compact('projects','news','members')]);
    }
}
