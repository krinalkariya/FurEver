<?php
  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $active = '';
  if (str_contains($path, '/about-public')) $active = 'about';
  elseif (str_contains($path, '/login')) $active = 'login';
  else $active = 'home';
?>
<nav class="navbar navbar-expand-lg sticky-top shadow-sm"
     style="background: linear-gradient(90deg,#6f42c1,#8f62ff); box-shadow:0 10px 24px rgba(111,66,193,.25);">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="/">
      <i class="bi bi-paw-fill me-1"></i> FurEver
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>
    <div id="publicNav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link fw-semibold <?= $active==='home'?'text-dark bg-white rounded px-3':'text-white' ?>" href="<?=APP_URL?>/">
            <i class="bi bi-house-door me-1"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-semibold <?= $active==='about'?'text-dark bg-white rounded px-3':'text-white' ?>" href="about-public">
            <i class="bi bi-info-circle me-1"></i> About
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>