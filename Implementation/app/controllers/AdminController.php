<?php

class AdminController
{
    private DBHelper $db;

    public function __construct()
    {
        $this->db = new DBHelper(DB::conn());
    }

    private function render(string $view, array $data = []): void
{
    // normalize keys so layout sees them
    if (isset($data['title']) && !isset($data['page_title'])) {
        $data['page_title'] = $data['title'];
    }
    if (!isset($data['active'])) {
        $data['active'] = '';
    }

    extract($data, EXTR_SKIP);

    ob_start();
    require __DIR__ . '/../views/' . $view . '.php'; // partial view only
    $content = ob_get_clean();

    require __DIR__ . '/../views/layout/admin_base.php'; // uses $page_title, $active, $content
}



    private function requireAdmin(): void
{
    $admin = Session::get('admin');

    if (!$admin || ($admin['role'] ?? null) !== 'admin') {
        Response::redirect(APP_URL . '/');
        exit;
    }

    // prevent cached access
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}
    public function dashboard(): void
    {
        $this->requireAdmin();
        $ngoCounts = $this->db->fetch("
            SELECT 
                SUM(CASE WHEN status='pending'  THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status='approved' THEN 1 ELSE 0 END) AS approved,
                SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) AS rejected
            FROM ngo
        ") ?: ['pending'=>0,'approved'=>0,'rejected'=>0];

        $userCounts = $this->db->fetch("
            SELECT 
                SUM(CASE WHEN status='active'   THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN status='inactive' THEN 1 ELSE 0 END) AS inactive
            FROM user_account
        ") ?: ['active'=>0,'inactive'=>0];

        $petCounts = $this->db->fetch("
            SELECT 
                SUM(CASE WHEN status='available' THEN 1 ELSE 0 END) AS available,
                SUM(CASE WHEN status='adopted'   THEN 1 ELSE 0 END) AS adopted,
                SUM(CASE WHEN status='inactive'  THEN 1 ELSE 0 END) AS inactive
            FROM pet
        ") ?: ['available'=>0,'adopted'=>0,'inactive'=>0];

        $recentReqs = $this->db->fetchAll("
            SELECT ar.request_id, ar.status, ar.created_at,
                   p.name AS pet_name, p.species, p.breed,
                   u.name AS user_name, n.name AS ngo_name
            FROM adoption_request ar
            JOIN pet p ON p.pet_id = ar.pet_id
            JOIN user_account u ON u.user_id = ar.user_id
            JOIN ngo n ON n.ngo_id = p.ngo_id
            ORDER BY ar.created_at DESC
            LIMIT 10
        ");

        $this->render('admin/dashboard', [
  'ngoCounts'=>$ngoCounts, 'userCounts'=>$userCounts, 'petCounts'=>$petCounts,
  'recentReqs'=>$recentReqs, 'active'=>'dashboard', 'page_title'=>'Admin Dashboard'
]);

    }

    public function ngosIndex(): void
    {
        $this->requireAdmin();
        $ngos = $this->db->fetchAll("
            SELECT ngo_id, name, email, phone, city, status, status_reason, created_at
            FROM ngo
            ORDER BY created_at DESC
        ");
        $this->render('admin/ngos', [
  'ngos'       => $ngos,
  'active'     => 'ngos',
  'page_title' => 'NGOs'
]);

    }

    public function ngoShow(): void
    {
        $this->requireAdmin();
        $ngo_id = (int)($_GET['id'] ?? 0);
        if ($ngo_id <= 0) { Response::redirect('admin/ngos'); return; }

        $ngo = $this->db->fetch("
            SELECT ngo_id, name, email, phone, city, status, status_reason, created_at
            FROM ngo WHERE ngo_id=?
        ", [$ngo_id]);
        if (!$ngo) { Session::flash('error','NGO not found.'); Response::redirect('admin/ngos'); return; }

        $pets = $this->db->fetchAll("
            SELECT pet_id, name, species, breed, sex, age, vaccinated, image, status, created_at
            FROM pet WHERE ngo_id=? ORDER BY created_at DESC
        ", [$ngo_id]);

        $this->render('admin/ngo_show', [
  'ngo'        => $ngo,
  'pets'       => $pets,
  'active'     => 'ngos',
  'page_title' => 'NGO · ' . ($ngo['name'] ?? 'Detail')
]);

    }

    public function approveNgoPost(): void
    {
        $this->requireAdmin();
        if (!CSRF::check($_POST['_csrf'] ?? null)) { Session::flash('error','Security token mismatch.'); Response::redirect('admin/ngos'); return; }
        $ngo_id = (int)($_GET['id'] ?? 0);
        if ($ngo_id > 0) {
            $this->db->execute("UPDATE ngo SET status='approved', status_reason=NULL, updated_at=NOW() WHERE ngo_id=?", [$ngo_id]);
            Session::flash('success','NGO approved.');
        }
        Response::redirect('admin/ngos');
    }

    public function rejectNgoPost(): void
    {
        $this->requireAdmin();
        if (!CSRF::check($_POST['_csrf'] ?? null)) { Session::flash('error','Security token mismatch.'); Response::redirect('admin/ngos'); return; }
        $ngo_id = (int)($_GET['id'] ?? 0);
        $reason = trim($_POST['status_reason'] ?? '');
        if ($ngo_id > 0) {
            $this->db->execute("UPDATE ngo SET status='rejected', status_reason=?, updated_at=NOW() WHERE ngo_id=?", [$reason ?: 'Rejected by admin', $ngo_id]);
            Session::flash('success','NGO rejected.');
        }
        Response::redirect('admin/ngos');
    }

    public function usersIndex(): void
    {
        $this->requireAdmin();
        $users = $this->db->fetchAll("
            SELECT user_id, name, email, phone, city, status, email_verified, created_at
            FROM user_account ORDER BY created_at DESC
        ");
        $this->render('admin/users', [
  'users'      => $users,
  'active'     => 'users',
  'page_title' => 'Users'
]);

    }

    public function usersTogglePost(): void
    {
        $this->requireAdmin();
        if (!CSRF::check($_POST['_csrf'] ?? null)) { Session::flash('error','Security token mismatch.'); Response::redirect('admin/users'); return; }
        $user_id = (int)($_GET['id'] ?? 0);
        if ($user_id > 0) {
            $status = $this->db->fetchValue("SELECT status FROM user_account WHERE user_id=?", [$user_id]);
            if ($status !== null) {
                $new = ($status === 'active') ? 'inactive' : 'active';
                $this->db->execute("UPDATE user_account SET status=?, updated_at=NOW() WHERE user_id=?", [$new, $user_id]);
                Session::flash('success','User status updated.');
            }
        }
        Response::redirect('admin/users');
    }

    public function petsIndex(): void
    {
        $this->requireAdmin();
        $pets = $this->db->fetchAll("
            SELECT p.pet_id, p.name, p.species, p.breed, p.sex, p.age, p.vaccinated, p.image, p.status, p.created_at,
                   n.ngo_id, n.name AS ngo_name, n.city AS ngo_city
            FROM pet p JOIN ngo n ON n.ngo_id = p.ngo_id
            ORDER BY p.created_at DESC
        ");
        $this->render('admin/pets', [
  'pets'       => $pets,
  'active'     => 'pets',
  'page_title' => 'All Pets'
]);

    }
}
