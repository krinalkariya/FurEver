<?php if (!isset($page_title)) { $page_title = 'FurEver Admin'; } ?>
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
      --brand-grad: linear-gradient(135deg,#6f42c1,#8f62ff);
      --brand-dark:#5a35a5;
      --brand-light:#9a7dff;
    }
    body.bg-light{ background:#f7f7fb !important; }

    /* Navbar - gradient background, white links */
    .navbar-admin{
      background:var(--brand-grad);
      box-shadow:0 4px 18px rgba(111,66,193,.25);
    }
    .navbar-admin .navbar-brand{
      display:flex; align-items:center; gap:.5rem; font-weight:800;
      color:#fff !important;
    }
    .navbar-admin .brand-badge{
      width:34px; height:34px; border-radius:10px; display:grid; place-items:center; color:#6f42c1;
      background:#fff; font-size:1rem; font-weight:bold;
    }

    .navbar-admin .nav-link{
      position:relative; font-weight:600; color:#fff !important; padding:.55rem .85rem;
      transition:.2s ease;
    }
    .navbar-admin .nav-link:hover{ color:#e6d9ff !important; }
    .navbar-admin .nav-link.active{
      color:#fff !important; background:rgba(255,255,255,.12); border-radius:.5rem;
    }
    .navbar-admin .nav-link::after{
      content:""; position:absolute; left:.85rem; right:.85rem; bottom:2px; height:3px;
      background:#fff; border-radius:3px; transform:scaleX(0); transform-origin:left; transition:.2s;
    }
    .navbar-admin .nav-link:hover::after, .navbar-admin .nav-link.active::after{
      transform:scaleX(1);
    }

    /* Flash + content */
    main .alert{ border-radius:.85rem; box-shadow:0 8px 22px rgba(0,0,0,.08); }

    .page-title{
      font-weight:800; margin-bottom:.75rem;
      color:var(--brand-dark);
      display:flex; align-items:center; gap:.5rem;
    }
    .page-title .dot{
      width:10px; height:10px; border-radius:50%; background:var(--brand-grad);
      box-shadow:0 0 0 3px rgba(111,66,193,.15);
    }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-admin">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin/dashboard">
      <span class="brand-badge"><i class="bi bi-shield-lock-fill"></i></span>
      FurEver Admin
    </a>
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#adminTopnav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="adminTopnav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link <?= ($active ?? '')==='dashboard'?'active':'' ?>" href="admin/dashboard"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '')==='ngos'?'active':'' ?>" href="admin/ngos"><i class="bi bi-building-check me-1"></i> NGOs</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '')==='users'?'active':'' ?>" href="admin/users"><i class="bi bi-people me-1"></i> Users</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '')==='pets'?'active':'' ?>" href="admin/pets"><i class="bi bi-heart-fill me-1"></i> Pets</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '')==='about'?'active':'' ?>" href="admin/about"><i class="bi bi-info-circle me-1"></i> About</a></li>
      </ul>
      <form class="ms-auto" method="POST" action="logout">
        <?= CSRF::input() ?>
        <button class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
      </form>
    </div>
  </div>
</nav>

<main class="container my-4">
  <?php if (!empty($page_title)): ?>
    <h4 class="page-title"><span class="dot"></span><?= htmlspecialchars($page_title) ?></h4>
  <?php endif; ?>

  <?php if (!empty($flash_success = Session::flash('success'))): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash_success) ?></div>
  <?php endif; ?>
  <?php if (!empty($flash_error = Session::flash('error'))): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($flash_error) ?></div>
  <?php endif; ?>

  <?= $content ?? '' ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/assets/js/session_check.js"></script>
</body>
</html>