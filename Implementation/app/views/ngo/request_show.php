<?php $active='requests'; $req=$req??[]; ?>
<div class="request-show-wrap py-2">

  <style>
    .card-glow{ border:0; border-radius:1rem; box-shadow:0 12px 28px rgba(0,0,0,.06); }
    .head-card{
      border:0; border-radius:1rem; padding:1rem 1.25rem;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);
      box-shadow:0 12px 28px rgba(0,0,0,.06);
    }
    .icon-pill{
      width:40px; height:40px; border-radius:12px; display:grid; place-items:center; color:#fff;
      background:linear-gradient(135deg,#6f42c1,#8f62ff); box-shadow:0 8px 20px rgba(111,66,193,.28); font-size:1.1rem;
    }

    .status-chip{
      display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.35rem .65rem;
      font-weight:700; font-size:.78rem; border:1px solid transparent;
    }
    .chip-pending{ color:#664d03; background:#fff3cd; border-color:#ffecb5; }
    .chip-approved{ color:#0f5132; background:#d1e7dd; border-color:#badbcc; }
    .chip-rejected{ color:#842029; background:#f8d7da; border-color:#f5c2c7; }

    .info-box{ border:1px dashed #e3defa; background:#faf8ff; border-radius:.75rem; padding:.75rem; }
    .mini-title{ font-weight:700; text-transform:uppercase; letter-spacing:.04em; font-size:.75rem; color:#6c6f8a; }

    .action-card{ border:0; border-radius:1rem; box-shadow:0 12px 28px rgba(0,0,0,.06); }
  </style>

  <!-- Header -->
  <?php
    $rs = strtolower($req['status'] ?? 'pending');
    $chip = $rs==='pending' ? 'chip-pending' : ($rs==='approved' ? 'chip-approved' : 'chip-rejected');
    $icon = $rs==='pending' ? 'bi-hourglass-split' : ($rs==='approved' ? 'bi-check-circle' : 'bi-x-circle');
  ?>
  <div class="head-card d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-3">
      <div class="icon-pill"><i class="bi bi-inbox"></i></div>
      <div>
        <h5 class="mb-0">Request #<?= (int)$req['request_id'] ?></h5>
        <div class="text-muted small">Applied on <?= htmlspecialchars($req['created_at']) ?></div>
      </div>
    </div>
    <span class="status-chip <?= $chip ?>"><i class="bi <?= $icon ?>"></i> <?= ucfirst($req['status']) ?></span>
  </div>

  <!-- Top details -->
  <div class="row g-3 mb-3">
    <!-- Pet -->
    <div class="col-md-6">
      <div class="card card-glow h-100">
        <div class="card-body">
          <div class="mini-title mb-2">Pet</div>
          <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="mb-1">
              <div class="fw-semibold"><?= htmlspecialchars($req['pet_name']) ?></div>
              <div class="text-muted small">ID: <?= (int)$req['pet_id'] ?></div>
            </div>
            <a class="btn btn-sm btn-outline-primary" href="ngo/pet?id=<?= (int)$req['pet_id'] ?>">
              <i class="bi bi-box-arrow-up-right me-1"></i> Open Pet
            </a>
          </div>
          <div class="info-box mt-2 small">
            <div><span class="text-muted">Status:</span> <strong><?= htmlspecialchars(ucfirst($req['status'])) ?></strong></div>
            <?php if (!empty($req['note'])): ?>
              <div class="mt-1"><span class="text-muted">Note:</span> <?= htmlspecialchars($req['note']) ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Applicant -->
    <div class="col-md-6">
      <div class="card card-glow h-100">
        <div class="card-body">
          <div class="mini-title mb-2">Applicant</div>
          <div class="fw-semibold"><?= htmlspecialchars($req['user_name']) ?></div>
          <div class="text-muted small">City: <?= htmlspecialchars($req['user_city']) ?></div>
          <div class="text-muted small">Phone: <?= htmlspecialchars($req['user_phone']) ?></div>
        </div>
      </div>
    </div>
  </div>

  <?php $isPending = (isset($req['status']) && $req['status'] === 'pending'); ?>

  <!-- Status banner when not pending -->
  <?php if (!$isPending): ?>
    <div class="alert <?= $req['status']==='approved' ? 'alert-success' : 'alert-danger' ?> d-flex justify-content-between align-items-center">
      <div class="me-3 mb-0">
        This request is <strong><?= htmlspecialchars(ucfirst($req['status'])) ?></strong>
        <?php if (!empty($req['note'])): ?> — Note: <?= htmlspecialchars($req['note']) ?><?php endif; ?>
      </div>
      <a class="btn btn-outline-secondary btn-sm" href="ngo/requests">Back to Requests</a>
    </div>
  <?php endif; ?>

  <!-- Actions when pending -->
  <?php if ($isPending): ?>
    <div class="card action-card">
      <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-start">
          <form method="POST" action="ngo/request/reject?id=<?= (int)$req['request_id'] ?>" class="d-inline">
            <?= CSRF::input() ?>
            <button class="btn btn-outline-danger">
              <i class="bi bi-x-circle me-1"></i> Reject
            </button>
          </form>

          <form method="POST" action="ngo/request/approve?id=<?= (int)$req['request_id'] ?>" class="d-inline">
            <?= CSRF::input() ?>
            <button class="btn btn-success">
              <i class="bi bi-check2-circle me-1"></i> Approve &amp; Mark Adopted
            </button>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($req['note'])): ?>
    <div class="mt-3 small text-muted">Existing note: <?= htmlspecialchars($req['note']) ?></div>
  <?php endif; ?>

</div>