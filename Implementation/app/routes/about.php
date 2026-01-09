<?php
// app/routes/about.php

$APP_DIR     = dirname(__DIR__);
$VIEW_FILE   = $APP_DIR . '/views/pages/about.php';
$USER_LAYOUT = $APP_DIR . '/views/layout/user_base.php';
$NGO_LAYOUT  = $APP_DIR . '/views/layout/ngo_base.php';

if (!is_file($VIEW_FILE)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "About view not found.\nExpected: {$VIEW_FILE}\n";
    exit;
}

$page_title = 'About Us · FurEver';
$active     = 'about';

ob_start();
require $VIEW_FILE;
$content = ob_get_clean();

// --- decide layout ---
// 1) explicit context from URL takes priority
$ctx = strtolower($_GET['ctx'] ?? '');
$useNgoLayout = ($ctx === 'ngo');

// 2) fallback: sessions (if you also want auto-detect)
if (!$useNgoLayout) {
    $sessUser = Session::get('user') ?: [];
    $sessNgo  = Session::get('ngo')  ?: [];
    if (!empty($sessNgo) || (($sessNgo['role'] ?? '') === 'ngo') || (($sessUser['role'] ?? '') === 'ngo')) {
        $useNgoLayout = true;
    }
}

if ($useNgoLayout && is_file($NGO_LAYOUT)) {
    require $NGO_LAYOUT;
} else {
    require $USER_LAYOUT;
}
