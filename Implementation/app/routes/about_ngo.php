<?php
// app/routes/about_ngo.php

$APP_DIR     = dirname(__DIR__); // .../app
$VIEW_FILE   = $APP_DIR . '/views/pages/about.php';
$NGO_LAYOUT  = $APP_DIR . '/views/layout/panel_base.php';

// safety check
if (!is_file($VIEW_FILE)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "About view not found for NGO.\nExpected: {$VIEW_FILE}\n";
    exit;
}

$page_title = 'About Us · FurEver';
$active     = 'about'; // highlight About in NGO nav

ob_start();
require $VIEW_FILE;
$content = ob_get_clean();

// Always render inside NGO base
require $NGO_LAYOUT;
