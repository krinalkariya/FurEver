<?php if (!isset($page_title)) { $page_title = 'FurEver'; } ?>
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
      --fur-primary:#6f42c1;
      --fur-primary-2:#8f62ff;
    }
    body{ background: linear-gradient(135deg,#ffffff 0%, #faf7ff 55%, #f4f7ff 100%); }

    /* NAVBAR — match user panel behavior */
    .navbar{
      background: linear-gradient(90deg, var(--fur-primary) 0%, var(--fur-primary-2) 40%, #6750d8 100%);
      box-shadow: 0 12px 28px rgba(111,66,193,.22);
    }
    .navbar .navbar-brand{
      color:#fff; font-weight:800; letter-spacing:.2px;
      display:flex; align-items:center; gap:.5rem;
    }
    .navbar .nav-link{
      color: rgba(255,255,255,.9);
      font-weight:600;
      position:relative;
      transition: color .15s ease;
    }
    .navbar .nav-link:hover{ color:#fff; }
    .navbar .nav-link.active{
      color:#fff;
    }
    .navbar .nav-link.active::after{
      content:""; position:absolute; left:.6rem; right:.6rem; bottom:-6px; height:3px;
      border-radius:3px; background:#fff; opacity:.95;
    }
    .navbar .btn-outline-danger{
      --bs-btn-color:#fff; --bs-btn-border-color:rgba(255,255,255,.6);
      --bs-btn-hover-bg:#dc3545; --bs-btn-hover-border-color:#dc3545;
      --bs-btn-hover-color:#fff; border-width:1.5px;
    }

    main.container{ margin-top:1rem; margin-bottom:2rem; }
    .alert{ border-radius:.85rem; }
    .status-chip{font-size:.75rem;padding:.25rem .5rem;border-radius:.5rem}
    .form-text.text-danger{display:block;margin-top:.25rem}
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="ngo/dashboard">
      <i class="bi bi-house-heart-fill me-1"></i> FurEver <span class="d-none d-sm-inline">(NGO)</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav" aria-controls="topnav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="topnav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?= ($active ?? '')==='dashboard'?'active':'' ?>" href="ngo/dashboard">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active ?? '')==='pets'?'active':'' ?>" href="ngo/pets">
            <i class="bi bi-heart me-1"></i> Pets
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active ?? '')==='requests'?'active':'' ?>" href="ngo/requests">
            <i class="bi bi-inbox me-1"></i> Requests
          </a>
        </li>
        <li class="nav-item">
  <a class="nav-link <?= ($active ?? '')==='about'?'active':'' ?>" href="ngo/about">
    <i class="bi bi-info-circle me-1"></i> About
  </a>
</li>
      </ul>

      <form class="ms-auto" method="POST" action="logout">
        <?= CSRF::input() ?>
        <button class="btn btn-outline-danger btn-sm">
          <i class="bi bi-box-arrow-right me-1"></i> Logout
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

  <?= $content ?? '' ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/assets/js/session_check.js"></script>
</body>
</html>
