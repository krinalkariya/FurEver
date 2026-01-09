<?php

class NGOController
{
    private DBHelper $db;

    public function __construct()
    {
        // Ensure session is started in public/index.php before routing
        $this->db = new DBHelper(DB::conn());
    }

    private function ngoId(): int {
        return (int)Session::get('ngo_id');
    }

    private function render(string $view, array $data = [])
    {
        extract($data);
        ob_start();
        require __DIR__ . "/../views/$view.php";
        $content = ob_get_clean();
        require __DIR__ . "/../views/layout/panel_base.php";
    }
    private function requireNgo(): void
{
    $ngo = Session::get('ngo');

    // if not logged in or role mismatch → redirect to landing
    if (!$ngo || ($ngo['role'] ?? null) !== 'ngo') {
        Response::redirect(APP_URL . '/');
        exit;
    }

    // enforce approved NGOs only
    if (($ngo['status'] ?? '') !== 'approved') {
        Session::flash('error', 'Your account is not approved yet.');
        Response::redirect(APP_URL . '/');
        exit;
    }

    // cache prevention headers (avoid back-button cached view)
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}
    /* ===== Dashboard ===== */
    public function dashboard()
{
    $this->requireNgo();
    $ngo_id = $this->ngoId();

    $stats = $this->db->fetch("
        SELECT
          SUM(CASE WHEN status='available' THEN 1 ELSE 0 END) AS available,
          SUM(CASE WHEN status='adopted' THEN 1 ELSE 0 END)   AS adopted,
          SUM(CASE WHEN status='inactive' THEN 1 ELSE 0 END)  AS inactive
        FROM pet WHERE ngo_id = ?", [$ngo_id]) ?? ['available'=>0,'adopted'=>0,'inactive'=>0];

    $pendingReqCount = (int)$this->db->fetchValue("
        SELECT COUNT(*) FROM adoption_request ar
        JOIN pet p ON p.pet_id = ar.pet_id
        WHERE p.ngo_id = ? AND ar.status='pending'", [$ngo_id]);

    $recentRequests = $this->db->fetchAll("
        SELECT ar.request_id, ar.status, ar.created_at,
               p.name AS pet_name, u.name AS user_name
        FROM adoption_request ar
        JOIN pet p ON p.pet_id = ar.pet_id
        JOIN user_account u ON u.user_id = ar.user_id
        WHERE p.ngo_id = ?
        ORDER BY ar.created_at DESC
        LIMIT 5", [$ngo_id]);

    $this->render('ngo/dashboard', [
        'page_title' => 'NGO Dashboard',
        'active' => 'dashboard',
        'stats' => $stats,
        'pendingReqCount' => $pendingReqCount,
        'recentRequests' => $recentRequests
    ]);
}


    /* ===== Pets ===== */
    public function pets()
    {
        $this->requireNgo();
        $ngo_id = $this->ngoId();
        $q = trim($_GET['q'] ?? '');
        $status = $_GET['status'] ?? '';
        $params = [$ngo_id];
        $where = "WHERE ngo_id = ?";
        if ($q !== '') {
            $where .= " AND (name LIKE ? OR breed LIKE ?)";
            $params[] = "%$q%"; $params[] = "%$q%";
        }
        if (in_array($status, ['available','adopted','inactive'], true)) {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $pets = $this->db->fetchAll("
            SELECT pet_id, name, species, breed, age, sex, vaccinated, status, image
            FROM pet $where
            ORDER BY updated_at DESC", $params);

        $this->render('ngo/pets', [
            'page_title' => 'My Pets',
            'active' => 'pets',
            'pets' => $pets,
            'q' => $q,
            'status' => $status
        ]);
    }

    public function petAddGet()
    {
    $this->requireNgo();
        $this->render('ngo/pet_add', [
            'page_title' => 'Add Pet',
            'active' => 'pets',
            'errors' => [],
            'old' => []
        ]);
    }

    public function petAddPost()
{
    $this->requireNgo();

    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error', 'Invalid session, please try again.');
        return Response::redirect(APP_URL.'/ngo/pet/add');
    }

    $ngo_id = $this->ngoId();
    $data = [
        'name'       => trim($_POST['name'] ?? ''),
        'species'    => trim($_POST['species'] ?? ''),
        'breed'      => trim($_POST['breed'] ?? ''),
        'age'        => trim($_POST['age'] ?? ''),
        'sex'        => trim($_POST['sex'] ?? ''),
        'vaccinated' => trim($_POST['vaccinated'] ?? ''),
        'description'=> trim($_POST['description'] ?? ''),
    ];

    // --- server-side validation (simple & explicit) ---
    $errors = [];

    if ($data['name'] === '' || mb_strlen($data['name']) < 2 || mb_strlen($data['name']) > 50) {
        $errors['name'] = 'Name is required (2–50 chars).';
    }
    if (!in_array($data['species'], ['dog','cat'], true)) {
        $errors['species'] = 'Select Dog or Cat.';
    }
    if ($data['breed'] === '' || mb_strlen($data['breed']) < 2 || mb_strlen($data['breed']) > 50) {
        $errors['breed'] = 'Breed is required (2–50 chars).';
    }
    if ($data['age'] === '' || !ctype_digit($data['age']) || (int)$data['age'] < 0 || (int)$data['age'] > 30) {
        $errors['age'] = 'Age must be a number between 0 and 30.';
    }
    if (!in_array($data['sex'], ['male','female'], true)) {
        $errors['sex'] = 'Select Male or Female.';
    }
    if (!in_array($data['vaccinated'], ['yes','no'], true)) {
        $errors['vaccinated'] = 'Select Yes or No.';
    }
    if ($data['description'] !== '' && mb_strlen($data['description']) > 500) {
        $errors['description'] = 'Max 500 characters.';
    }

    // Image (optional)
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $tmp  = $_FILES['image']['tmp_name'];
        $size = (int)($_FILES['image']['size'] ?? 0);
        $mime = $tmp && is_file($tmp) ? (mime_content_type($tmp) ?: '') : '';

        if (!in_array($mime, ['image/jpeg','image/png'], true) || $size > 2*1024*1024) {
            $errors['image'] = 'Only JPG/PNG up to 2MB allowed.';
        }
    }

    if (!empty($errors)) {
        // re-render with per-field errors & old values
        return $this->render('ngo/pet_add', [
            'page_title' => 'Add Pet',
            'active'     => 'pets',
            'errors'     => $errors,
            'old'        => $data
        ]);
    }

    // Save file if provided
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $dir = __DIR__ . "/../../public/uploads/pets/$ngo_id";
        if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "$dir/$filename");
        $imagePath = "uploads/pets/$ngo_id/$filename";  
    }

    // Insert
    $this->db->execute("
        INSERT INTO pet (ngo_id, name, species, breed, age, sex, vaccinated, description, image, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'available', NOW(), NOW())
    ", [
        $ngo_id,
        $data['name'],
        $data['species'],
        $data['breed'],
        (int)$data['age'],
        $data['sex'],
        $data['vaccinated'],
        $data['description'] !== '' ? $data['description'] : null,
        $imagePath
    ]);

    Session::flash('success', 'Pet added successfully.');
    return Response::redirect(APP_URL.'/ngo/pets');
}


    public function petEditGet($pet_id)
    {
    $this->requireNgo();
        $ngo_id = $this->ngoId();
        $pet = $this->db->fetch("SELECT * FROM pet WHERE pet_id=? AND ngo_id=?", [(int)$pet_id, $ngo_id]);
        if (!$pet) { return Response::abort(404); }

        $this->render('ngo/pet_edit', [
            'page_title' => 'Edit Pet',
            'active' => 'pets',
            'errors' => [],
            'old' => $pet,
            'pet' => $pet
        ]);
    }

    public function petEditPost($pet_id)
{
    $this->requireNgo();
    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error', 'Invalid session, please try again.');
        return Response::redirect(APP_URL.'/ngo/pet/'.(int)$pet_id.'/edit');
    }

    $ngo_id = $this->ngoId();
    $pet = $this->db->fetch("SELECT * FROM pet WHERE pet_id=? AND ngo_id=?", [(int)$pet_id, $ngo_id]);
    if (!$pet) { return Response::abort(404); }

    $data = [
        'name'       => trim($_POST['name'] ?? ''),
        'species'    => trim($_POST['species'] ?? ''),
        'breed'      => trim($_POST['breed'] ?? ''),
        'age'        => trim($_POST['age'] ?? ''),
        'sex'        => trim($_POST['sex'] ?? ''),
        'vaccinated' => trim($_POST['vaccinated'] ?? ''),
        'status'     => trim($_POST['status'] ?? $pet['status']),
        'description'=> trim($_POST['description'] ?? ''),
    ];

    $errors = [];

    if ($data['name'] === '' || mb_strlen($data['name']) < 2 || mb_strlen($data['name']) > 50) {
        $errors['name'] = 'Name is required (2–50 chars).';
    }
    if (!in_array($data['species'], ['dog','cat'], true)) {
        $errors['species'] = 'Select Dog or Cat.';
    }
    if ($data['breed'] === '' || mb_strlen($data['breed']) < 2 || mb_strlen($data['breed']) > 50) {
        $errors['breed'] = 'Breed is required (2–50 chars).';
    }
    if ($data['age'] === '' || !ctype_digit($data['age']) || (int)$data['age'] < 0 || (int)$data['age'] > 30) {
        $errors['age'] = 'Age must be a number between 0 and 30.';
    }
    if (!in_array($data['sex'], ['male','female'], true)) {
        $errors['sex'] = 'Select Male or Female.';
    }
    if (!in_array($data['vaccinated'], ['yes','no'], true)) {
        $errors['vaccinated'] = 'Select Yes or No.';
    }
    if (!in_array($data['status'], ['available','inactive','adopted'], true)) {
        $errors['status'] = 'Invalid status.';
    }
    if ($data['description'] !== '' && mb_strlen($data['description']) > 500) {
        $errors['description'] = 'Max 500 characters.';
    }

    // Image (optional)
    $imagePath = $pet['image'];
    if (!empty($_FILES['image']['name'])) {
        $tmp  = $_FILES['image']['tmp_name'];
        $size = (int)($_FILES['image']['size'] ?? 0);
        $mime = $tmp && is_file($tmp) ? (mime_content_type($tmp) ?: '') : '';

        if (!in_array($mime, ['image/jpeg','image/png'], true) || $size > 2*1024*1024) {
            $errors['image'] = 'Only JPG/PNG up to 2MB allowed.';
        }
    }

    if (!empty($errors)) {
        return $this->render('ngo/pet_edit', [
            'page_title' => 'Edit Pet',
            'active'     => 'pets',
            'errors'     => $errors,
            'old'        => $data + $pet,
            'pet'        => $pet
        ]);
    }

    // Save new image if uploaded
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $dir = __DIR__ . "/../../public/uploads/pets/$ngo_id";
        if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "$dir/$filename");
        $imagePath = "uploads/pets/$ngo_id/$filename";  
    }

    $this->db->execute("
        UPDATE pet SET
            name=?, species=?, breed=?, age=?, sex=?, vaccinated=?, description=?, image=?, status=?, updated_at=NOW()
        WHERE pet_id=? AND ngo_id=?",
        [
            $data['name'],
            $data['species'],
            $data['breed'],
            (int)$data['age'],
            $data['sex'],
            $data['vaccinated'],
            $data['description'] !== '' ? $data['description'] : null,
            $imagePath,
            $data['status'],
            (int)$pet_id,
            $ngo_id
        ]
    );

    Session::flash('success', 'Pet updated.');
    return Response::redirect(APP_URL.'/ngo/pets');
}


    public function petShow($pet_id)
    {
    $this->requireNgo();
        $ngo_id = $this->ngoId();
        $pet = $this->db->fetch("SELECT * FROM pet WHERE pet_id=? AND ngo_id=?", [(int)$pet_id, $ngo_id]);
        if (!$pet) { return Response::abort(404); }

        $requests = $this->db->fetchAll("
            SELECT ar.request_id, ar.status, ar.created_at, u.name AS user_name, u.city AS user_city
            FROM adoption_request ar
            JOIN user_account u ON u.user_id = ar.user_id
            WHERE ar.pet_id=?
            ORDER BY ar.created_at DESC", [(int)$pet_id]);

        $this->render('ngo/pet_show', [
            'page_title' => 'Pet Details',
            'active' => 'pets',
            'pet' => $pet,
            'requests' => $requests
        ]);
    }

    public function petSetInactivePost($pet_id)
{
    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error', 'Invalid session, please try again.');
        return $this->petShow($pet_id);
    }
    $ngo_id = $this->ngoId();
    $this->db->execute("UPDATE pet SET status='inactive', updated_at=NOW() WHERE pet_id=? AND ngo_id=?", [(int)$pet_id, $ngo_id]);
    Session::flash('success', 'Pet set to inactive.');

    // Instead of redirecting, just call the show page again
    return $this->petShow($pet_id);
}

public function petMarkAdoptedPost($pet_id)
{
    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error', 'Invalid session, please try again.');
        return $this->petShow($pet_id);
    }
    $ngo_id = $this->ngoId();
    $this->db->beginTransaction();
    try {
        $this->db->execute("UPDATE pet SET status='adopted', adopted_at=NOW(), updated_at=NOW() WHERE pet_id=? AND ngo_id=?", [(int)$pet_id, $ngo_id]);
        $this->db->execute("UPDATE adoption_request SET status='rejected', note='Pet adopted' WHERE pet_id=? AND status='pending'", [(int)$pet_id]);
        $this->db->commit();
        Session::flash('success', 'Pet marked adopted.');
    } catch (Exception $e) {
        $this->db->rollBack();
        Session::flash('error', 'Action failed.');
    }

    // Show updated page instantly
    return $this->petShow($pet_id);
}


    /* ===== Requests ===== */
    public function requests()
{
    $this->requireNgo();
    $ngo_id = $this->ngoId();
    $status = $_GET['status'] ?? '';
    $where = "WHERE p.ngo_id = ?";
    $params = [$ngo_id];
    if (in_array($status, ['pending','approved','rejected'], true)) {
        $where .= " AND ar.status = ?";
        $params[] = $status;
    }

    $requests = $this->db->fetchAll("
        SELECT ar.request_id, ar.status, ar.created_at,
               p.name AS pet_name, u.name AS user_name, u.city AS user_city
        FROM adoption_request ar
        JOIN pet p ON p.pet_id = ar.pet_id
        JOIN user_account u ON u.user_id = ar.user_id
        $where
        ORDER BY ar.created_at DESC", $params);

    $this->render('ngo/requests', [
        'page_title' => 'Requests',
        'active' => 'requests',
        'requests' => $requests,
        'status' => $status
    ]);
}

// ---- detail page ----
public function requestShow($request_id)
{
    $this->requireNgo();
    $ngo_id = $this->ngoId();
    if ($request_id <= 0) { return Response::abort(404); }

    $req = $this->db->fetch("
        SELECT ar.*, p.name AS pet_name, p.pet_id, u.name AS user_name, u.city AS user_city, u.phone AS user_phone
        FROM adoption_request ar
        JOIN pet p ON p.pet_id = ar.pet_id
        JOIN user_account u ON u.user_id = ar.user_id
        WHERE ar.request_id=? AND p.ngo_id=?", [(int)$request_id, $ngo_id]);

    if (!$req) { return Response::abort(404); }

    $this->render('ngo/request_show', [
        'page_title' => 'Request Detail',
        'active' => 'requests',
        'req' => $req,
        'errors' => []
    ]);
}

// ---- approve ----
public function requestApprovePost($request_id)
{
    $this->requireNgo();
    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error', 'Invalid session, please try again.');
        return Response::redirect(APP_URL.'/ngo/request?id='.(int)$request_id);
    }

    $ngo_id = $this->ngoId();
    $row = $this->db->fetch("
        SELECT ar.pet_id
        FROM adoption_request ar
        JOIN pet p ON p.pet_id = ar.pet_id
        WHERE ar.request_id=? AND p.ngo_id=?", [(int)$request_id, $ngo_id]);
    if (!$row) { return Response::abort(404); }

    $pet_id = (int)$row['pet_id'];

    $this->db->beginTransaction();
    try {
        // approve this request
        $this->db->execute("UPDATE adoption_request SET status='approved' WHERE request_id=?", [(int)$request_id]);

        // mark pet adopted
        $this->db->execute("UPDATE pet SET status='adopted', adopted_at=NOW(), updated_at=NOW() WHERE pet_id=?", [$pet_id]);

        // close all other pending requests for this pet
        $this->db->execute("UPDATE adoption_request SET status='rejected', note='Pet adopted' WHERE pet_id=? AND status='pending'", [$pet_id]);

        $this->db->commit();
        Session::flash('success', 'Request approved and pet marked adopted.');
    } catch (Exception $e) {
        $this->db->rollBack();
        Session::flash('error', 'Approval failed.');
    }

    return Response::redirect(APP_URL.'/ngo/request?id='.(int)$request_id);
}

public function requestRejectPost($request_id)
{
    $this->requireNgo();
    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error', 'Invalid session, please try again.');
        return Response::redirect(APP_URL.'/ngo/request?id='.(int)$request_id);
    }

    $ngo_id = $this->ngoId();
    $ok = $this->db->fetch("
        SELECT ar.request_id
        FROM adoption_request ar
        JOIN pet p ON p.pet_id = ar.pet_id
        WHERE ar.request_id=? AND p.ngo_id=?", [(int)$request_id, $ngo_id]);
    if (!$ok) { return Response::abort(404); }

    $note = trim($_POST['note'] ?? '');
    if (mb_strlen($note) > 200) {
        Session::flash('error', 'Note too long (max 200 chars).');
        return Response::redirect(APP_URL.'/ngo/request?id='.(int)$request_id);
    }

    // just set status and optional note; no updated_at column in this table
    $this->db->execute(
        "UPDATE adoption_request SET status='rejected', note=? WHERE request_id=?",
        [$note !== '' ? $note : null, (int)$request_id]
    );

    Session::flash('success', 'Request rejected.');
    return Response::redirect(APP_URL.'/ngo/request?id='.(int)$request_id);
}
}
