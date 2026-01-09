<?php
if (!isset($page_title)) { $page_title = 'FurEver'; }
$tab = '';
if (isset($active)) {
  $tab = strtolower(trim($active));
} else {
  // Fallback: infer from URL path if controller didn't set $active
  $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
  if (str_contains($path, '/user/pets'))       $tab = 'pets';
  elseif (str_contains($path, '/user/requests')) $tab = 'requests';
  elseif (str_contains($path, '/user/profile'))  $tab = 'profile';
  elseif (str_contains($path, '/about')) $tab = 'about';
  elseif (str_contains($path, '/user/lostfound')) $tab ='lostfound';
  else  $tab = 'dashboard';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($page_title) ?> · FurEver</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?= htmlspecialchars(rtrim(APP_URL, '/')) ?>/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --fur-primary:#6f42c1;     /* violet */
      --fur-primary-2:#8f62ff;   /* lighter violet */
      --fur-accent:#ffb703;      /* warm accent */
      --fur-ink:#29293a;
      --fur-muted:#f6f7fb;
    }

    /* Soft, modern background with a gentle diagonal gradient */
    body{
      background:
        linear-gradient(135deg, #ffffff 0%, #faf7ff 55%, #f4f7ff 100%);
      min-height:100vh;
      color: var(--fur-ink);
    }

    /* NAVBAR — glassy gradient bar, underline hover, no circular brand element */
    .navbar {
      background: linear-gradient(90deg, var(--fur-primary) 0%, var(--fur-primary-2) 35%, #6750d8 100%);
      box-shadow: 0 10px 24px rgba(111,66,193,.25);
    }
    .navbar .navbar-brand{
      color:#fff; font-weight:800; letter-spacing:.3px;
    }
    .navbar .navbar-brand:hover{ color:#fff; opacity:.95; }
    .navbar .navbar-toggler{
      border-color: rgba(255,255,255,.4);
    }
    .navbar .navbar-toggler-icon{
      filter: invert(1) grayscale(1) brightness(1.8);
    }

    /* Nav links: subtle hover + animated underline; active = white pill */
    .navbar .nav-link{
      color: rgba(255,255,255,.9);
      font-weight:600;
      position:relative;
      border-radius:.5rem;
      padding:.5rem .85rem;
      transition: color .2s ease, background .2s ease;
    }
    .navbar .nav-link::after{
      content:""; position:absolute; left:.85rem; right:.85rem; bottom:.35rem;
      height:2px; background: rgba(255,255,255,.55);
      transform: scaleX(0); transform-origin: left; transition: transform .22s ease;
    }
    .navbar .nav-link:hover{ color:#fff; background: rgba(255,255,255,.08); }
    .navbar .nav-link:hover::after{ transform: scaleX(1); }
    .navbar .nav-link.active{
      color:#111 !important; background:#fff; box-shadow: 0 8px 18px rgba(0,0,0,.12);
    }
    .navbar .nav-link.active::after{ display:none; }

    /* Logout button: outline on dark gradient */
    .btn-logout{
      --bs-btn-color:#fff;
      --bs-btn-border-color:rgba(255,255,255,.65);
      --bs-btn-hover-bg:#dc3545;
      --bs-btn-hover-border-color:#dc3545;
      --bs-btn-hover-color:#fff;
      --bs-btn-active-bg:#c72f3d;
      --bs-btn-active-border-color:#c72f3d;
      border-width:1.5px;
    }

    /* Page spacing + elevated flash */
    main.container{ margin-top:1.35rem; margin-bottom:2.75rem; }
    .alert{ border-radius:.8rem; box-shadow: 0 10px 26px rgba(0,0,0,.06); }

    /* Card feel for inner content without changing your structure */
    .content-frame{
      background:#fff; border-radius:1rem; padding:1.25rem;
      box-shadow: 0 18px 40px rgba(111,66,193,.10);
    }

    /* Keep validation helpers exactly as you had */
    .nav-link.active{font-weight:600}
    .form-text.text-danger{display:block;margin-top:.25rem}
    .status-chip{font-size:.75rem;padding:.25rem .5rem;border-radius:.5rem}
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="user/dashboard">
      <i class="bi bi-paw-fill me-2"></i>FurEver <span class="opacity-75 fw-normal">User</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav" aria-controls="topnav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="topnav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto my-2 my-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= ($tab ?? '')==='dashboard'?'active':'' ?>" href="user/dashboard">
            <i class="bi bi-speedometer2 me-1"></i>Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($tab ?? '')==='pets'?'active':'' ?>" href="user/pets">
            <i class="bi bi-heart-fill me-1"></i>Browse Pets
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($tab ?? '')==='requests'?'active':'' ?>" href="user/requests">
            <i class="bi bi-inbox-fill me-1"></i>My Requests
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($tab ?? '')==='profile'?'active':'' ?>" href="user/profile">
            <i class="bi bi-person-circle me-1"></i>Profile
          </a>
        </li>
        <li class="nav-item">
  <a class="nav-link <?= ($tab ?? '')==='lostfound'?'active':'' ?>" href="user/lostfound">
    <i class="bi bi-search me-1"></i> Lost & Found
  </a>
</li>
        <li class="nav-item">
  <a class="nav-link <?= (($active ?? '')==='about') ? 'active' : '' ?>" href="about">
    <i class="bi bi-info-circle me-1"></i> About
  </a>
</li>

      </ul>

      <form class="ms-auto" method="POST" action="logout">
        <?= CSRF::input() ?>
        <button class="btn btn-outline-light btn-sm btn-logout">
          <i class="bi bi-box-arrow-right me-1"></i>Logout
        </button>
      </form>
    </div>
  </div>
</nav>

<main class="container my-4">
  <?php if (!empty($flash_success = Session::flash('success'))): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash_success) ?></div>
  <?php endif; ?>
  <?php if (!empty($flash_error = Session::flash('error'))): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($flash_error) ?></div>
  <?php endif; ?>

  <!-- Keep your content exactly where it was; just a subtle frame -->
  <div class="content-frame">
    <?= $content ?? '' ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/assets/js/session_check.js"></script>
</body>
</html>
