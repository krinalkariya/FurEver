<?php $active = 'dashboard'; ?>
<div class="admin-dashboard py-3">

  <style>
    /* Scoped to admin dashboard */
    .hero-admin{
      position:relative;border-radius:1rem;overflow:hidden;color:#fff;min-height:220px;
      background:linear-gradient(135deg,#6f42c1 0%, #8f62ff 100%);
      box-shadow:0 18px 44px rgba(111,66,193,.25);
    }
    .hero-admin img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:.18; }
    .hero-admin .content{ position:relative; z-index:2; padding:1.25rem 1rem; }
    @media (min-width:992px){ .hero-admin .content{ padding:1.75rem; } }
    .hero-badge{
      background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.28);
      backdrop-filter:blur(6px); font-weight:600; border-radius:999px; padding:.35rem .7rem;
      display:inline-flex; gap:.4rem; align-items:center;
    }

    /* Stat tiles */
    .stat-card{
      border:0; border-radius:1rem; background:#fff;
      box-shadow:0 12px 28px rgba(0,0,0,.08);
      transition:transform .18s ease, box-shadow .18s ease;
      height:100%;
    }
    .stat-card:hover{ transform:translateY(-3px); box-shadow:0 22px 44px rgba(111,66,193,.18); }
    .stat-icon{
      width:44px; height:44px; border-radius:12px; display:grid; place-items:center; color:#fff;
      background:linear-gradient(135deg,#6f42c1,#8f62ff); box-shadow:0 8px 20px rgba(111,66,193,.28); font-size:1.2rem;
    }
    .stat-value{ font-size:1.8rem; font-weight:800; line-height:1; }
    .muted{ color:#6c757d; }

    /* Section cards */
    .section-card{ border:0; border-radius:1rem; box-shadow:0 12px 28px rgba(0,0,0,.06); }

    /* Modern table (like NGO) */
    .table-modern{ border-collapse:separate; border-spacing:0 10px; width:100%; }
    .table-modern thead th{
      position:sticky; top:0; z-index:1; background:#f6f4ff; color:#5a5a7a;
      font-weight:700; border:none; padding:.75rem .9rem; text-transform:uppercase;
      font-size:.75rem; letter-spacing:.04em; border-top-left-radius:.75rem; border-top-right-radius:.75rem;
    }
    .table-modern tbody tr{ background:#fff; box-shadow:0 6px 18px rgba(0,0,0,.05); }
    .table-modern tbody td{ border-top:none; border-bottom:none; vertical-align:middle; padding:.85rem .9rem; }
    .table-modern tbody tr:hover{ transform:translateY(-2px); box-shadow:0 12px 28px rgba(111,66,193,.12) }

    .badge-soft{
      display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.3rem .55rem; font-weight:700; font-size:.78rem;
    }
    .soft-approved{ color:#0f5132; background:#d1e7dd; border:1px solid #badbcc; }
    .soft-rejected{ color:#842029; background:#f8d7da; border:1px solid #f5c2c7; }
    .soft-pending{ color:#41464b; background:#e2e3e5; border:1px solid #d3d6d8; }

    .empty-block{
      border-radius:1rem; padding:1.25rem; text-align:center;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%); box-shadow:0 14px 34px rgba(111,66,193,.12)
    }
    .empty-block .bi{ font-size:1.4rem; color:#6f42c1; }
  </style>

  <!-- HERO -->
  <section class="hero-admin mb-4">
    <div class="content">
      <div class="d-flex flex-wrap gap-2 mb-2">
        <span class="hero-badge"><i class="bi bi-shield-lock"></i> Admin Console</span>
        <span class="hero-badge"><i class="bi bi-building-check"></i> NGO Verification</span>
        <span class="hero-badge"><i class="bi bi-activity"></i> Platform Health</span>
      </div>
      <h2 class="fw-bold mb-1">Overview & Controls</h2>
      <p class="mb-0">Monitor NGOs, users, pets, and requests at a glance. Keep everything safe, transparent, and running smoothly.</p>
      <div class="mt-3 d-flex flex-wrap gap-2">
        <a href="admin/ngos" class="btn btn-light btn-sm"><i class="bi bi-clipboard2-check me-1"></i> Review NGO Applications</a>
        <a href="admin/pets" class="btn btn-outline-light btn-sm"><i class="bi bi-grid-3x3-gap me-1"></i> Manage Pets</a>
      </div>
    </div>
  </section>

  <!-- QUICK STATS -->
  <section class="mb-3">
    <div class="row g-3">
      <!-- NGOs -->
      <div class="col-12 col-md-4">
        <div class="card stat-card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center gap-2">
                <div class="stat-icon"><i class="bi bi-building"></i></div>
                <div class="fw-bold">NGOs</div>
              </div>
              <a href="admin/ngos" class="btn btn-sm btn-outline-primary">Open</a>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-1">
              <div><span class="muted small d-block">Pending</span><div class="stat-value"><?= (int)$ngoCounts['pending'] ?></div></div>
              <div><span class="muted small d-block">Approved</span><div class="stat-value"><?= (int)$ngoCounts['approved'] ?></div></div>
              <div><span class="muted small d-block">Rejected</span><div class="stat-value"><?= (int)$ngoCounts['rejected'] ?></div></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Users -->
      <div class="col-12 col-md-4">
        <div class="card stat-card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center gap-2">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="fw-bold">Users</div>
              </div>
              <a href="admin/users" class="btn btn-sm btn-outline-primary">Open</a>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-1">
              <div><span class="muted small d-block">Active</span><div class="stat-value"><?= (int)$userCounts['active'] ?></div></div>
              <div><span class="muted small d-block">Inactive</span><div class="stat-value"><?= (int)$userCounts['inactive'] ?></div></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Pets -->
      <div class="col-12 col-md-4">
        <div class="card stat-card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center gap-2">
                <div class="stat-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="fw-bold">Pets</div>
              </div>
              <a href="admin/pets" class="btn btn-sm btn-outline-primary">Open</a>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-1">
              <div><span class="muted small d-block">Available</span><div class="stat-value"><?= (int)$petCounts['available'] ?></div></div>
              <div><span class="muted small d-block">Adopted</span><div class="stat-value"><?= (int)$petCounts['adopted'] ?></div></div>
              <div><span class="muted small d-block">Inactive</span><div class="stat-value"><?= (int)$petCounts['inactive'] ?></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- RECENT ADOPTION REQUESTS -->
  <section class="mb-1">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h5 class="mb-0">Recent Adoption Requests</h5>
      <a href="admin/pets" class="btn btn-sm btn-outline-primary"><i class="bi bi-view-list me-1"></i>Browse Pets</a>
    </div>

    <div class="card section-card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-modern align-middle mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pet</th>
                <th>User</th>
                <th>NGO</th>
                <th>Status</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentReqs as $r): ?>
                <?php
                  $s = strtolower($r['status']);
                  $cls = $s==='approved' ? 'soft-approved' : ($s==='rejected' ? 'soft-rejected' : 'soft-pending');
                ?>
                <tr>
                  <td>#<?= (int)$r['request_id'] ?></td>
                  <td>
                    <div class="fw-semibold"><?= htmlspecialchars($r['pet_name']) ?></div>
                    <div class="text-muted small"><?= htmlspecialchars(ucfirst($r['species'])) ?> / <?= htmlspecialchars($r['breed']) ?></div>
                  </td>
                  <td><?= htmlspecialchars($r['user_name']) ?></td>
                  <td><?= htmlspecialchars($r['ngo_name']) ?></td>
                  <td><span class="badge-soft <?= $cls ?> text-uppercase"><?= htmlspecialchars($r['status']) ?></span></td>
                  <td class="text-muted small"><?= htmlspecialchars($r['created_at']) ?></td>
                </tr>
              <?php endforeach; ?>

              <?php if (empty($recentReqs)): ?>
                <tr>
                  <td colspan="6">
                    <div class="empty-block">
                      <i class="bi bi-inbox"></i>
                      <div class="fw-bold mt-1">No requests yet</div>
                      <div class="text-muted small">You’ll see new adoption requests here as they come in.</div>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</div>