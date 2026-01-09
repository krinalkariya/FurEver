<?php $active='pets'; ?>
<div class="pet-show-wrap py-2">

  <style>
    /* Scoped styling */
    .card-glow{ border:0; border-radius:1rem; box-shadow:0 12px 28px rgba(0,0,0,.06); }
    .head-block{
      border:0; border-radius:1rem; padding:1rem 1.25rem;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);
      box-shadow:0 12px 28px rgba(0,0,0,.06);
    }
    .pet-media{
      width:100%; height:260px; object-fit:cover; border-radius:.9rem;
      background:#f3f1ff;
    }
    .no-media{
      height:260px; border-radius:.9rem; background:#f3f1ff;
      display:flex; align-items:center; justify-content:center; color:#6c757d; font-weight:600;
      box-shadow:inset 0 0 0 2px #e5e3ff;
    }
    .meta-pill{
      display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.3rem .6rem;
      background:#fff; border:1px solid rgba(111,66,193,.25); color:#6f42c1; font-weight:600; font-size:.78rem;
    }
    .status-soft{ font-weight:700; font-size:.78rem; border-radius:999px; padding:.3rem .6rem; display:inline-flex; align-items:center; gap:.35rem; }
    .st-available{ color:#0f5132; background:#d1e7dd; border:1px solid #badbcc; }
    .st-adopted{ color:#41464b; background:#e2e3e5; border:1px solid #d3d6d8; }
    .st-inactive{ color:#664d03; background:#fff3cd; border:1px solid #ffecb5; }

    /* Requests table */
    .table-modern{ border-collapse:separate; border-spacing:0 10px; width:100%; }
    .table-modern thead th{
      position:sticky; top:0; z-index:1; background:#f6f4ff; color:#5a5a7a;
      font-weight:700; border:none; padding:.75rem .9rem; text-transform:uppercase; font-size:.75rem; letter-spacing:.04em;
      border-top-left-radius:.75rem; border-top-right-radius:.75rem;
    }
    .table-modern tbody tr{ background:#fff; box-shadow:0 6px 18px rgba(0,0,0,.05); }
    .table-modern tbody td{ border-top:none; border-bottom:none; vertical-align:middle; padding:.85rem .9rem; }
    .table-modern tbody tr:hover{ transform:translateY(-2px); box-shadow:0 12px 28px rgba(111,66,193,.12) }

    .empty-block{
      border-radius:1rem; padding:1.25rem; text-align:center;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%); box-shadow:0 14px 34px rgba(111,66,193,.12)
    }
  </style>

  <div class="row g-3">
    <!-- LEFT: Pet summary -->
    <div class="col-lg-5">
      <div class="head-block mb-3 d-flex align-items-center gap-3">
        <div>
          <div class="fw-bold fs-5 mb-0"><?= htmlspecialchars($pet['name']) ?></div>
          <div class="text-muted small">#<?= (int)$pet['pet_id'] ?></div>
        </div>
        <div class="ms-auto">
          <?php
            $st = strtolower($pet['status']);
            $stClass = $st==='available' ? 'st-available' : ($st==='adopted' ? 'st-adopted' : 'st-inactive');
          ?>
          <span class="status-soft <?= $stClass ?>"><?= ucfirst($pet['status']) ?></span>
        </div>
      </div>

      <div class="card card-glow mb-3">
        <div class="card-body">
          <?php
            $img = $pet['image'];
            if ($img && $img[0] === '/') { $img = ltrim($img, '/'); }
          ?>
          <?php if(!empty($img)): ?>
            <img src="<?= htmlspecialchars($img) ?>" alt="Pet photo" class="pet-media mb-3">
          <?php else: ?>
            <div class="no-media mb-3">No image available</div>
          <?php endif; ?>

          <div class="d-flex flex-wrap gap-2 mb-2">
            <span class="meta-pill"><i class="bi bi-heart-fill"></i> <?= htmlspecialchars(ucfirst($pet['species'])) ?></span>
            <span class="meta-pill"><i class="bi bi-tags"></i> <?= htmlspecialchars($pet['breed']) ?></span>
            <span class="meta-pill"><i class="bi bi-calendar2"></i> <?= (int)$pet['age'] ?> yrs</span>
            <span class="meta-pill"><i class="bi bi-gender-ambiguous"></i> <?= htmlspecialchars(ucfirst($pet['sex'])) ?></span>
            <span class="meta-pill"><i class="bi bi-shield-check"></i> <?= $pet['vaccinated']==='yes'?'Vaccinated':'Not vaccinated' ?></span>
          </div>

          <?php if(!empty($pet['description'])): ?>
            <div class="mt-2">
              <div class="fw-semibold mb-1">Description</div>
              <div class="text-muted"><?= nl2br(htmlspecialchars($pet['description'])) ?></div>
            </div>
          <?php endif; ?>

          <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="ngo/pet/edit?id=<?= (int)$pet['pet_id'] ?>" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-pencil-square me-1"></i> Edit
            </a>

            <form method="POST" action="ngo/pet/inactive?id=<?= (int)$pet['pet_id'] ?>" class="d-inline">
              <?= CSRF::input() ?>
              <button class="btn btn-outline-dark btn-sm" <?= $pet['status']==='inactive'?'disabled':'' ?>>
                <i class="bi bi-dash-circle me-1"></i> Set Inactive
              </button>
            </form>

            <form method="POST" action="ngo/pet/mark-adopted?id=<?= (int)$pet['pet_id'] ?>" class="d-inline">
              <?= CSRF::input() ?>
              <button class="btn btn-outline-success btn-sm" <?= $pet['status']==='adopted'?'disabled':'' ?>>
                <i class="bi bi-check2-circle me-1"></i> Mark Adopted
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT: Requests -->
    <div class="col-lg-7">
      <div class="card card-glow">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
          <span class="fw-semibold">Requests</span>
          <a class="btn btn-sm btn-outline-primary" href="ngo/requests"><i class="bi bi-view-list me-1"></i> View all</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
              <thead>
                <tr>
                  <th>#</th><th>Applicant</th><th>City</th><th>Status</th><th>Created</th><th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($requests as $r): ?>
                  <?php
                    $rs = strtolower($r['status']);
                    $chip = $rs==='pending' ? 'warning' : ($rs==='approved' ? 'success' : 'danger');
                    $icon = $rs==='pending' ? 'bi-hourglass-split' : ($rs==='approved' ? 'bi-check-circle' : 'bi-x-circle');
                  ?>
                  <tr>
                    <td>#<?= (int)$r['request_id'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['user_name']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($r['user_city']) ?></td>
                    <td>
                      <span class="badge text-bg-<?= $chip ?>"><i class="bi <?= $icon ?>"></i> <?= ucfirst($r['status']) ?></span>
                    </td>
                    <td class="text-muted small"><?= htmlspecialchars($r['created_at']) ?></td>
                    <td class="text-center text-nowrap">
                      <a href="ngo/request?id=<?= (int)$r['request_id'] ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye me-1"></i> View
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>

                <?php if (empty($requests)): ?>
                  <tr>
                    <td colspan="6">
                      <div class="empty-block">
                        <div class="fw-bold">No requests yet</div>
                        <div class="text-muted small">You’ll see adoption requests for this pet here.</div>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>