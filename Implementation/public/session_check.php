<?php
require _DIR_ . '/../app/core/Session.php';
Session::start();

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$user  = Session::get('user');
$ngo   = Session::get('ngo');
$admin = Session::get('admin');

$logged_in = ($user || $ngo || $admin);
echo json_encode(['logged_in' => $logged_in]);