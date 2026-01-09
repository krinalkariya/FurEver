<?php
require __DIR__.'/../app/config/env.php';
spl_autoload_register(function($c){
  foreach (['config','core','controllers'] as $dir) {
    $f = __DIR__."/../app/$dir/$c.php"; if (file_exists($f)) { require $f; return; }
  }
});
Session::start();
$router = new Router();
$router->get('/', function() {
    $user = Session::get('user');
    if ($user && ($user['role'] ?? '') === 'user') {
        Response::redirect(APP_URL . '/user/dashboard');
        return;
    }
    if ($user && ($user['role'] ?? '') === 'ngo') {
        Response::redirect(APP_URL . '/ngo/dashboard');
        return;
    }
    require __DIR__ . '/../app/views/landing.php';
});

/** AUTH SCREENS (use auth layout) */
$router->get('/login', fn()=> Response::view('auth/login', ['title'=>'Login'], 'layout/auth_base'));
$router->post('/login', [new AuthController, 'login']);

$router->get('/register/user', fn()=> Response::view('auth/register_user', ['title'=>'Create User'], 'layout/auth_base'));
$router->post('/register/user', [new AuthController, 'registerUser']);

$router->get('/register/ngo', fn()=> Response::view('auth/register_ngo', ['title'=>'Register NGO'], 'layout/auth_base'));
$router->post('/register/ngo', [new AuthController, 'registerNgo']);

$router->get('/verify', fn()=> Response::view('auth/verify', ['title'=>'Verify Email'], 'layout/auth_base'));
$router->post('/verify', [new AuthController, 'verify']);
$router->post('/resend-otp', [new AuthController, 'resendOtp']);
$router->get('/ngo/status', fn()=> Response::view('auth/ngo_status', ['title'=>'NGO Approval Status'], 'layout/auth_base'));
$router->post('/ngo/status', [new AuthController, 'ngoStatus']);



$router->post('/logout', [new AuthController, 'logout']);
// NGO — DASHBOARD & LISTS
$router->get('/ngo/dashboard', function () {
  (new NGOController())->dashboard();
});
// Request detail: GET /ngo/request?id=123
$router->get('/ngo/request', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->requestShow($id);
});

// Approve: POST /ngo/request/approve?id=123
$router->post('/ngo/request/approve', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->requestApprovePost($id);
});

// Reject: POST /ngo/request/reject?id=123
$router->post('/ngo/request/reject', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->requestRejectPost($id);
});
$router->get('/ngo/pets', function () {
  (new NGOController())->pets();
});

// NGO — ADD PET
$router->get('/ngo/pet/add', function () {
  (new NGOController())->petAddGet();
});
$router->post('/ngo/pet/add', function () {
  (new NGOController())->petAddPost();
});

// NGO — PET SHOW / EDIT
// VIEW PET (GET /ngo/pet?id=123)
$router->get('/ngo/pet', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->petShow($id);
});

// EDIT PET (GET /ngo/pet/edit?id=123)
$router->get('/ngo/pet/edit', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->petEditGet($id);
});

// EDIT PET SAVE (POST /ngo/pet/edit?id=123)
$router->post('/ngo/pet/edit', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->petEditPost($id);
});

// SET INACTIVE (POST /ngo/pet/inactive?id=123)
$router->post('/ngo/pet/inactive', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->petSetInactivePost($id);
});

// MARK ADOPTED (POST /ngo/pet/mark-adopted?id=123)
$router->post('/ngo/pet/mark-adopted', function () {
  $id = (int)($_GET['id'] ?? 0);
  (new NGOController())->petMarkAdoptedPost($id);
});

// NGO — REQUESTS
$router->get('/ngo/requests', function () {
  (new NGOController())->requests();
});



$router->get('/user/dashboard', function() {
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->dashboard();
});

$router->get('/user/pets', function() {
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->pets();
});

$router->get('/user/pet', function() { // expects ?id=123
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->petShow();
});

$router->post('/user/adopt', function() { // expects ?id=123
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->apply();
});

$router->get('/user/requests', function() {
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->requests();
});

$router->get('/user/profile', function() {
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->profileGet();
});

$router->post('/user/profile', function() {
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->profilePost();
});
// GET Lost & Found page
$router->get('/user/lostfound', function() {
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->lostFoundGet();
});

// POST Lost & Found submit
$router->post('/user/lostfound', function() {
    require_once __DIR__ . '/../app/controllers/UserController.php';
    (new UserController)->lostFoundPost();
});

$admin = new AdminController();

// Admin Dashboard
$adminGuard = function() {
    $a = Session::get('admin');
    if (!$a || ($a['role'] ?? null) !== 'admin') {
        Response::redirect('/');
        exit;
    }
};
$router->get('/login', fn()=> Response::view('auth/login', [
  'title'=>'Login',
  'suppressFlash'=>true   // no top alert on login
], 'layout/auth_base'));
// Admin — Dashboard
$router->get('/admin/dashboard', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->dashboard();
});

// Admin — NGOs
$router->get('/admin/ngos', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->ngosIndex();
});
$router->get('/admin/ngo', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->ngoShow();
});
$router->post('/admin/ngos/approve', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->approveNgoPost();
});
$router->post('/admin/ngos/reject', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->rejectNgoPost();
});

// Admin — Users
$router->get('/admin/users', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->usersIndex();
});
$router->post('/admin/users/toggle', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->usersTogglePost();
});

// Admin — Pets
$router->get('/admin/pets', function() use ($adminGuard) {
    $adminGuard();
    (new AdminController)->petsIndex();
});
$aboutFile = __DIR__ . '/../app/routes/about.php';
$router->get('/about', $aboutFile);
$router->get('about',  $aboutFile);
$router->get('/ngo/about', __DIR__ . '/../app/routes/about_ngo.php');
$router->get('ngo/about',  __DIR__ . '/../app/routes/about_ngo.php');
$router->get('admin/about', __DIR__ . '/../app/routes/about_admin.php');
$router->get('/admin/about', __DIR__ . '/../app/routes/about_admin.php');
$router->get('/about-public', fn() => require __DIR__ . '/../app/views/about_public.php');

$router->dispatch();
