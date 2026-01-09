<?php /* $title, $viewPath provided by Response::view */ ?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($title??'FurEver') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= APP_URL ?>/login">FurEver</a>
    <div>
      <?php if(!Auth::user()): ?>
        <a class="btn btn-outline-primary btn-sm" href="<?= APP_URL ?>/login">Login</a>
      <?php else: ?>
        <form method="post" action="<?= APP_URL ?>/logout" class="d-inline">
          <button class="btn btn-danger btn-sm">Logout</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</nav>
<main class="container py-4">
  <?php if($m=Session::flash('success')): ?><div class="alert alert-success"><?= htmlspecialchars($m) ?></div><?php endif; ?>
  <?php if($m=Session::flash('error')): ?><div class="alert alert-danger"><?= htmlspecialchars($m) ?></div><?php endif; ?>
  <?php require $viewPath; ?>
</main>
</body></html>
