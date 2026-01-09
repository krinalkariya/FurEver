<?php
// app/routes/about_admin.php
$APP_DIR     = dirname(__DIR__); // .../app
$VIEW_FILE   = $APP_DIR . '/views/pages/about.php';            // reuse same about view
$ADMIN_LAYOUT= $APP_DIR . '/views/layout/admin_base.php';

if (!is_file($VIEW_FILE)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "About view not found.\nExpected: {$VIEW_FILE}\n"; exit;
}

$page_title = 'About Us · FurEver';
$active     = 'about';

ob_start();
require $VIEW_FILE;     // renders into $content
$content = ob_get_clean();

require $ADMIN_LAYOUT;  // admin layout wrapper