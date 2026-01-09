<?php $active='pets'; ?>

<div class="ngo-pets-wrap py-2">

  <style>
    .search-group .form-control:focus {
    box-shadow: none;
    border-color: #ced4da; /* keep neutral border */
  }
    /* Scoped styling */
    .toolbar-card{
      border:0; border-radius:1rem; background:#fff;
      box-shadow:0 12px 28px rgba(0,0,0,.06);
    }
    .chip{
      display:inline-flex; align-items:center; gap:.45rem;
      border:1.5px solid rgba(111,66,193,.3);
      background:#fff; border-radius:999px;
      padding:.45rem .85rem; font-weight:600; font-size:.92rem;
      text-decoration:none; transition:.15s;
      box-shadow:0 8px 18px rgba(111,66,193,.08);
      color:#5a4ca3;
    }
    .chip:hover{ background:#f4efff; border-color:#8f62ff; box-shadow:0 14px 28px rgba(111,66,193,.18); }
    .chip.active{ background:#6f42c1; color:#fff; border-color:#6f42c1; box-shadow:0 10px 26px rgba(111,66,193,.35); }
    .chip .bi{opacity:.9}

    .pet-card{
      border:0; border-radius:1rem; overflow:hidden; height:100%;
      background:#fff; box-shadow:0 12px 32px rgba(0,0,0,.07);
      transition:transform .18s ease, box-shadow .18s ease;
    }
    .pet-card:hover{ transform:translateY(-4px); box-shadow:0 24px 48px rgba(111,66,193,.16); }
    .pet-media{ width:100%; aspect-ratio:4/3; object-fit:cover; display:block; }
    .pet-badge{
      position:absolute; top:.6rem; left:.6rem;
      background:rgba(0,0,0,.58); color:#fff; font-size:.8rem;
      border-radius:.5rem; padding:.2rem .5rem; backdrop-filter:blur(2px);
    }
    .pet-age{
      position:absolute; top:.6rem; right:.6rem;
      background:#fff; font-weight:700; font-size:.8rem; color:#111;
      border-radius:.5rem; padding:.2rem .5rem; box-shadow:0 8px 18px rgba(0,0,0,.15);
    }
    .pet-meta{ color:#6c757d; font-size:.95rem; }
    .badge-soft{
      border:1px solid rgba(111,66,193,.25);
      background:#f8f3ff; color:#6f42c1; border-radius:999px; padding:.15rem .5rem; font-size:.75rem;
      white-space:nowrap;
    }
    .status-soft{
      font-weight:700; font-size:.78rem; border-radius:999px; padding:.3rem .6rem; display:inline-flex; align-items:center; gap:.35rem;
    }
    .st-available{ color:#0f5132; background:#d1e7dd; border:1px solid #badbcc; }
    .st-adopted{ color:#41464b; background:#e2e3e5; border:1px solid #d3d6d8; }
    .st-inactive{ color:#664d03; background:#fff3cd; border:1px solid #ffecb5; }

    .empty-block{
      border-radius:1rem; padding:1.25rem; text-align:center;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%); box-shadow:0 14px 34px rgba(111,66,193,.12)
    }
    .empty-block .bi{ font-size:1.6rem; color:#6f42c1; }
  </style>

  <!-- Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h4 class="mb-0">My Pets</h4>
    <a href="ngo/pet/add" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Pet</a>
  </div>

  <!-- Toolbar: search + chips -->
  <div class="card toolbar-card mb-3">
    <div class="card-body">
      <form class="row g-2 align-items-center" method="GET" action="ngo/pets">
        <div class="col-12 col-md-6">
         <div class="input-group search-group">
  <span class="input-group-text"><i class="bi bi-search"></i></span>
  <input type="text" class="form-control" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search...">
  <button class="btn btn-primary" type="submit"><i class="bi bi-arrow-right"></i></button>
</div>

        </div>
        <div class="col-12 col-md-6">
          <div class="d-flex flex-wrap gap-2 justify-content-md-end">
            <?php
              $statusVal = $status ?? '';
              // helpers for href building — preserve q while switching status
              $base = 'ngo/pets';
              $qParam = strlen(trim($q ?? '')) ? '&q='.urlencode($q) : '';
            ?>
            <a class="chip <?= $statusVal===''?'active':'' ?>" href="<?= $base ?>?status=<?= '' . $qParam ?>">
              <i class="bi bi-grid"></i> All
            </a>
            <a class="chip <?= $statusVal==='available'?'active':'' ?>" href="<?= $base ?>?status=available<?= $qParam ?>">
              <i class="bi bi-paw"></i> Available
            </a>
            <a class="chip <?= $statusVal==='adopted'?'active':'' ?>" href="<?= $base ?>?status=adopted<?= $qParam ?>">
              <i class="bi bi-heart-fill"></i> Adopted
            </a>
            <a class="chip <?= $statusVal==='inactive'?'active':'' ?>" href="<?= $base ?>?status=inactive<?= $qParam ?>">
              <i class="bi bi-pause-circle"></i> Inactive
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php if (empty($pets)): ?>
    <div class="empty-block">
      <i class="bi bi-paw"></i>
      <div class="fw-bold mt-1">No pets yet</div>
      <div class="text-muted small">Start by adding your first pet listing.</div>
      <a href="ngo/pet/add" class="btn btn-primary btn-sm mt-2">Add Pet</a>
    </div>
  <?php else: ?>

    <!-- Grid of pet cards -->
    <div class="row g-3">
      <?php foreach ($pets as $p): ?>
        <?php
          $img = $p['image'];
          if ($img && $img[0] === '/') { $img = ltrim($img, '/'); }
          $st  = strtolower($p['status']);
          $stClass = $st==='available' ? 'st-available' : ($st==='adopted' ? 'st-adopted' : 'st-inactive');
        ?>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
          <div class="card pet-card">
            <div class="position-relative">
              <?php if (!empty($img)): ?>
                <img src="<?= htmlspecialchars($img) ?>" class="pet-media" alt="pet">
              <?php else: ?>
                <img src="assets/images/placeholder_pet.jpg" class="pet-media" alt="pet">
              <?php endif; ?>
              <span class="pet-badge"><?= htmlspecialchars(ucfirst($p['species'])) ?></span>
              <span class="pet-age"><?= (int)$p['age'] ?>y</span>
            </div>
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between">
                <div>
                  <div class="fw-bold"><?= htmlspecialchars($p['name']) ?></div>
                  <div class="pet-meta"><?= htmlspecialchars($p['breed']) ?> • <?= htmlspecialchars(ucfirst($p['sex'])) ?></div>
                </div>
                <span class="status-soft <?= $stClass ?>">
                  <?php if ($st==='available'): ?><i class="bi bi-paw"></i><?php elseif ($st==='adopted'): ?><i class="bi bi-heart-fill"></i><?php else: ?><i class="bi bi-pause-circle"></i><?php endif; ?>
                  <?= ucfirst($st) ?>
                </span>
              </div>

              <div class="d-flex flex-wrap gap-2 mt-2">
                <span class="badge-soft"><i class="bi bi-shield-check me-1"></i><?= ($p['vaccinated']==='yes'?'Vaccinated':'Not vaccinated') ?></span>
                <!-- <span class="badge-soft">#<?= (int)$p['pet_id'] ?></span> -->
              </div>

              <div class="d-flex gap-2 mt-3">
                <a href="ngo/pet?id=<?= (int)$p['pet_id'] ?>" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye me-1"></i> View
                </a>
                <a href="ngo/pet/edit?id=<?= (int)$p['pet_id'] ?>" class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>

</div>
