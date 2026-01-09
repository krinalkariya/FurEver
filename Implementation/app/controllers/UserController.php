<?php

class UserController
{
    private DBHelper $db;

    public function __construct()
    {
        $this->db = new DBHelper(DB::conn());
        $this->requireUser();
    }

    private function requireUser(): void
{
    // 🧱 Prevent back navigation cache at browser level
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: 0");

    // 🧩 Check session validity
    $user = Session::get('user');
    if (!$user || ($user['role'] ?? null) !== 'user' || 
        (int)($user['email_verified'] ?? 0) !== 1 || 
        ($user['status'] ?? 'inactive') !== 'active') {

        // 🧠 Output small JS fallback to prevent back-cache view
        echo "<script>
          window.location.replace('" . APP_URL . "/');
        </script>";
        exit;
    }
}

    private function currentUserId(): int
    {
        $user = Session::get('user');
        return (int)$user['user_id'];
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout/user_base.php';
    }

    // GET /user/dashboard
    public function dashboard(): void
    {
        $userId = $this->currentUserId();

        $userId = $_SESSION['user_id'] ?? null;
        $totalAvailable = (int)$this->db->fetchValue("
            SELECT COUNT(*) 
            FROM pet p 
            JOIN ngo n ON n.ngo_id = p.ngo_id
            WHERE p.status='available' AND n.status='approved'
        ");
        $activeRequests = (int)$this->db->fetchValue("
            SELECT COUNT(*) 
            FROM adoption_request ar
            WHERE ar.user_id = :uid AND ar.status IN ('pending','approved')
        ", ['uid' => $userId]);

        // Filter logic (same as pets page)
        $species    = isset($_GET['species'])    ? trim($_GET['species'])    : '';
        $breed      = isset($_GET['breed'])      ? trim($_GET['breed'])      : '';
        $city       = isset($_GET['city'])       ? trim($_GET['city'])       : '';
        $ageband    = isset($_GET['ageband'])    ? trim($_GET['ageband'])    : '';
        $vaccinated = isset($_GET['vaccinated']) ? trim($_GET['vaccinated']) : '';

        $where = ["p.status='available'", "n.status='approved'"];
        $params = [];

        if ($species !== '') {
            $where[] = "p.species = :species";
            $params['species'] = $species;
        }
        if ($breed !== '') {
            $where[] = "p.breed = :breed";
            $params['breed'] = $breed;
        }
        if ($city !== '') {
            $where[] = "n.city = :city";
            $params['city'] = $city;
        }
        if ($vaccinated !== '') {
            $where[] = "p.vaccinated = :vaccinated";
            $params['vaccinated'] = $vaccinated;
        }
        if ($ageband !== '') {
            if ($ageband === 'puppy') {
                $where[] = "p.age < 2";
            } elseif ($ageband === 'young') {
                $where[] = "p.age >= 2 AND p.age <= 6";
            } elseif ($ageband === 'senior') {
                $where[] = "p.age > 6";
            }
        }

        $sql = "SELECT p.pet_id, p.name, p.species, p.breed, p.age, p.image, n.city, p.vaccinated
                FROM pet p
                JOIN ngo n ON n.ngo_id = p.ngo_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY p.created_at DESC
                LIMIT 8";
        $recentPets = $this->db->fetchAll($sql, $params);

        $this->render('user/dashboard', [
           'totalAvailable' => $totalAvailable,
           'activeRequests' => $activeRequests,
           'recentPets' => $recentPets,
        ]);
    }

    // GET /user/pets
    public function pets() {
        $this->requireUser();

        $species = trim($_GET['species'] ?? '');
        $breed   = trim($_GET['breed'] ?? '');
        $city    = trim($_GET['city'] ?? '');
        $ageband = trim($_GET['ageband'] ?? '');
        $vaccinated = trim($_GET['vaccinated'] ?? '');   // <-- add this

        $sql = "SELECT p.pet_id, p.name, p.species, p.breed, p.age, p.sex, p.vaccinated, p.image, n.city, n.name AS ngo_name
                FROM pet p
                JOIN ngo n ON n.ngo_id = p.ngo_id
                WHERE p.status='available' AND n.status='approved'";
        $params = [];

        if ($species) { $sql .= " AND p.species = ?"; $params[] = $species; }
        if ($breed)   { $sql .= " AND p.breed LIKE ?"; $params[] = "%$breed%"; }
        if ($city)    { $sql .= " AND n.city = ?"; $params[] = $city; }
        if ($vaccinated !== '') { $sql .= " AND p.vaccinated = ?"; $params[] = $vaccinated; } 

        if ($ageband) {
            switch($ageband) {
                case 'puppy': $sql .= " AND p.age BETWEEN 0 AND 1"; break;
                case 'young': $sql .= " AND p.age BETWEEN 1 AND 3"; break;
                case 'adult': $sql .= " AND p.age BETWEEN 3 AND 7"; break;
                case 'senior':$sql .= " AND p.age >= 7"; break;
            }
        }

        $sql .= " ORDER BY p.created_at DESC";

        $pets = $this->db->fetchAll($sql, $params);

        $this->render('user/pets', [
            'title' => 'Browse Pets',
            'pets'  => $pets,
            'active'=> 'pets',
            'filters' => [
                'species' => $species,
                'breed'   => $breed,
                'city'    => $city,
                'ageband' => $ageband,
                    'vaccinated' => $vaccinated,  
            ]
        ]);
    }

    private function ageRangeForBand(string $band): array
    {
        switch ($band) {
            case 'puppy_kitten': return [0, 1];
            case 'young':        return [1, 3];
            case 'adult':        return [3, 7];
            case 'senior':       return [7, null];
            default:             return [null, null];
        }
    }

    // GET /user/pet?id=123
    public function petShow(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            Session::flash('error', 'Invalid pet.');
            Response::redirect('user/pets');
            return;
        }

        $pet = $this->db->fetch("
            SELECT p.*, n.name AS ngo_name, n.city AS ngo_city
            FROM pet p
            JOIN ngo n ON n.ngo_id = p.ngo_id
            WHERE p.pet_id = :id AND n.status='approved'
        ", ['id' => $id]);

        if (!$pet) {
            Session::flash('error', 'Pet not found.');
            Response::redirect('user/pets');
            return;
        }

        // 🔍 Check if user already applied
        $applied = $this->db->fetch("
            SELECT status 
            FROM adoption_request 
            WHERE pet_id = :pid AND user_id = :uid
            LIMIT 1
        ", ['pid' => $id, 'uid' => $this->currentUserId()]);

        $this->render('user/pet_show', [
            'pet'     => $pet,
            'applied' => $applied, // null if not applied
        ]);
    }

    // POST /user/adopt?id=123
    public function apply(): void
    {
        if (!CSRF::check($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Security token mismatch.');
            $this->petShow();
            return;
        }

        $petId  = (int)($_GET['id'] ?? 0);
        $userId = $this->currentUserId();

        if ($petId <= 0) {
            Session::flash('error', 'Invalid pet.');
            Response::redirect('user/pets');
            return;
        }

        try {
            $pdo = DB::conn();
            $pdo->beginTransaction();

            // lock pet row to avoid race
            $petRow = $this->db->fetch("
                SELECT p.status
                FROM pet p
                WHERE p.pet_id = :pid
                FOR UPDATE
            ", ['pid' => $petId]);

            if (!$petRow) {
                $pdo->rollBack();
                Session::flash('error', 'Pet not found.');
                Response::redirect('user/pets');
                return;
            }
            if ($petRow['status'] !== 'available') {
                $pdo->rollBack();
                Session::flash('error', 'Sorry, this pet is not available for adoption.');
                $this->petShow();
                return;
            }

            // insert adoption request
            $this->db->execute("
                INSERT INTO adoption_request (pet_id, user_id, status, note, created_at)
                VALUES (:pid, :uid, 'pending', NULL, NOW())
            ", ['pid' => $petId, 'uid' => $userId]);

            $pdo->commit();
            Session::flash('success', 'Your adoption request has been submitted!');
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // Duplicate (unique pet_id+user_id)
            if ($e->getCode() === '23000' && isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                Session::flash('warning', 'You have already applied for this pet.');
            } else {
                Session::flash('error', 'Could not submit your request. Please try again.');
            }
        }

        // Re-render pet detail so user sees updated flash instantly
        $this->petShow();
    }

    // GET /user/requests
    public function requests(): void
    {
        $userId = $this->currentUserId();

        $rows = $this->db->fetchAll("
            SELECT ar.request_id, ar.status, ar.created_at,
                   p.pet_id, p.name AS pet_name, p.image, p.species, p.breed
            FROM adoption_request ar
            JOIN pet p ON p.pet_id = ar.pet_id
            WHERE ar.user_id = :uid
            ORDER BY ar.created_at DESC
        ", ['uid' => $userId]);

        $this->render('user/requests', [
            'requests' => $rows,
        ]);
    }

    // GET /user/profile
    public function profileGet(): void
    {
        $uid = $this->currentUserId();
        $user = $this->db->fetch("SELECT user_id, name, email, phone, city FROM user_account WHERE user_id = :id", ['id' => $uid]);

        if (!$user) {
            Session::flash('error', 'User not found.');
            Response::redirect('user/dashboard');
            return;
        }

        $this->render('user/profile', [
            'user' => $user,
            'errors' => [],
            'old' => [],
        ]);
    }

    // POST /user/profile
    public function profilePost(): void
    {
        if (!CSRF::check($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Security token mismatch.');
            Response::redirect('user/profile');
            return;
        }

        $uid = $this->currentUserId();
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $city = trim($_POST['city'] ?? '');

        $errors = [];

        if ($name === '' || strlen($name) < 2) {
            $errors['name'] = 'Please enter a valid name.';
        }
        if ($city === '' || strlen($city) < 2) {
            $errors['city'] = 'Please enter a valid city.';
        }
        if ($phone === '' || !preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = 'Enter a valid 10-digit phone number.';
        }

        if (!empty($errors)) {
            $user = ['user_id'=>$uid, 'name'=>$name, 'email'=>'', 'phone'=>$phone, 'city'=>$city];
            $existing = $this->db->fetch("SELECT email FROM user_account WHERE user_id=:id", ['id'=>$uid]);
            $user['email'] = $existing ? $existing['email'] : '';
            $this->render('user/profile', ['user' => $user, 'errors' => $errors, 'old' => $_POST]);
            return;
        }

        try {
            $this->db->execute("
                UPDATE user_account 
                   SET name=:name, phone=:phone, city=:city, updated_at=NOW()
                 WHERE user_id=:id
            ", ['name'=>$name, 'phone'=>$phone, 'city'=>$city, 'id'=>$uid]);

            // Keep session phone/name in sync if you store them
            $sess = Session::get('user');
            if ($sess) {
                $sess['name'] = $name;
                $sess['phone'] = $phone;
                $sess['city'] = $city;
                Session::set('user', $sess);
            }

            Session::flash('success', 'Profile updated.');
            Response::redirect('user/profile');
        } catch (PDOException $e) {
            if ($e->getCode() === '23000' && isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                $user = $this->db->fetch("SELECT user_id, name, email, phone, city FROM user_account WHERE user_id=:id", ['id'=>$uid]);
                $errors['phone'] = 'This phone number is already in use.';
                $this->render('user/profile', ['user' => $user, 'errors' => $errors, 'old' => $_POST]);
                return;
            }
            Session::flash('error', 'Could not update profile.');
            Response::redirect('user/profile');
        }
    }

    // =========================
    // ONLY ADDITION STARTS HERE
    // =========================
    /** Reusable helper for future pages (NOT used anywhere yet). */
    private function fetchPetsFiltered(array $filters = [], int $limit = 0): array
    {
        $species = trim($filters['species'] ?? '');
        $breed   = trim($filters['breed']   ?? '');
        $city    = trim($filters['city']    ?? '');
        $ageband = trim($filters['ageband'] ?? '');

        $sql = "SELECT p.pet_id, p.name, p.species, p.breed, p.age, p.sex, p.vaccinated, p.image, n.city, n.name AS ngo_name
                FROM pet p
                JOIN ngo n ON n.ngo_id = p.ngo_id
                WHERE p.status='available' AND n.status='approved'";
        $params = [];

        if ($species) { $sql .= " AND p.species = ?"; $params[] = $species; }
        if ($breed)   { $sql .= " AND p.breed LIKE ?"; $params[] = "%$breed%"; }
        if ($city)    { $sql .= " AND n.city = ?"; $params[] = $city; }

        if ($ageband) {
            switch($ageband) {
                case 'puppy': $sql .= " AND p.age BETWEEN 0 AND 1"; break;
                case 'young': $sql .= " AND p.age BETWEEN 1 AND 3"; break;
                case 'adult': $sql .= " AND p.age BETWEEN 3 AND 7"; break;
                case 'senior':$sql .= " AND p.age >= 7"; break;
            }
        }

        $sql .= " ORDER BY p.created_at DESC";
        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return $this->db->fetchAll($sql, $params);
    }
    // GET /user/lostfound
public function lostFoundGet(): void
{
    $rows = $this->db->fetchAll("
        SELECT lf.*, u.name, u.city, u.phone, u.email
        FROM lost_found lf
        JOIN user_account u ON u.user_id = lf.user_id
        ORDER BY lf.created_at DESC
    ");

    $this->render('user/lostfound', [
        'reports' => $rows,
        'errors'  => [],
        'old'     => []
    ]);
}

// POST /user/lostfound
public function lostFoundPost(): void
{
    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error', 'Security token mismatch.');
        Response::redirect('user/lostfound');
        return;
    }

    $uid   = $this->currentUserId();
    $type  = trim($_POST['type'] ?? '');
    $image = $_FILES['image'] ?? null;

    $errors = [];
    $allowedTypes = ['image/jpeg','image/png','image/jpg'];

    if ($type !== 'lost' && $type !== 'found') {
        $errors['type'] = 'Please select Lost or Found.';
    }
    if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
        $errors['image'] = 'Please upload an image.';
    } elseif (!in_array(mime_content_type($image['tmp_name']), $allowedTypes)) {
        $errors['image'] = 'Image must be JPG or PNG.';
    } elseif ($image['size'] > 2*1024*1024) {
        $errors['image'] = 'Image must be under 2MB.';
    }

    if (!empty($errors)) {
        $rows = $this->db->fetchAll("
            SELECT lf.*, u.name, u.city, u.phone, u.email
            FROM lost_found lf
            JOIN user_account u ON u.user_id = lf.user_id
            ORDER BY lf.created_at DESC
        ");
        $this->render('user/lostfound', [
            'reports' => $rows,
            'errors'  => $errors,
            'old'     => $_POST
        ]);
        return;
    }

    // Save file
    $dir = "uploads/lostfound/" . $uid;
    if (!is_dir($dir)) { mkdir($dir, 0777, true); }
    $fname = uniqid('lf_', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
    $path = $dir . '/' . $fname;
    move_uploaded_file($image['tmp_name'], $path);

    $this->db->execute("
        INSERT INTO lost_found (user_id, type, image, created_at)
        VALUES (:uid, :type, :img, NOW())
    ", ['uid'=>$uid, 'type'=>$type, 'img'=>$path]);

    Session::flash('success','Your report has been added.');
    Response::redirect('user/lostfound');
}
    // =======================
    // ONLY ADDITION ENDS HERE
    // =======================
}
