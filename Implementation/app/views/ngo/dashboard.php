<?php $active='dashboard'; ?>

<div class="ngo-dashboard py-3">

  <style>
    /* Scoped to NGO dashboard */
    .hero-ngo{
      position:relative;border-radius:1rem;overflow:hidden;color:#fff;min-height:240px;
      background:#111; box-shadow:0 18px 44px rgba(111,66,193,.18);
    }
    .hero-ngo img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:.5; transform:scale(1.02); }
    .hero-ngo .mask{ position:absolute; inset:0;
      background:linear-gradient(135deg, rgba(15,15,25,.7), rgba(90,60,160,.55));
    }
    .hero-ngo .content{ position:relative; z-index:2; padding:1.25rem 1rem; }
    @media (min-width:992px){ .hero-ngo .content{ padding:1.75rem; } }
    .hero-badge{ background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
      backdrop-filter:blur(6px); font-weight:600; border-radius:999px; padding:.35rem .7rem; display:inline-flex; gap:.4rem; align-items:center;
    }

    /* Stat cards */
    .stat-card{
      border:0; border-radius:1rem; background:#fff;
      box-shadow:0 12px 28px rgba(13,110,253,.10);
      transition:transform .18s ease, box-shadow .18s ease;
    }
    .stat-card:hover{ transform:translateY(-3px); box-shadow:0 22px 44px rgba(111,66,193,.18); }
    .stat-icon{
      width:44px; height:44px; border-radius:12px; display:grid; place-items:center; color:#fff;
      background:linear-gradient(135deg,#6f42c1,#8f62ff); box-shadow:0 8px 20px rgba(111,66,193,.28); font-size:1.2rem;
    }
    .stat-icon.blue{ background:linear-gradient(135deg,#0d6efd,#6ea8fe); }
    .stat-icon.green{ background:linear-gradient(135deg,#198754,#63d3a6); }
    .stat-icon.gray{ background:linear-gradient(135deg,#6c757d,#9aa0a6); }
    .stat-value{ font-size:1.75rem; font-weight:800; line-height:1; }

    /* Quick actions — enriched */
    .quick-card{
      border:0; border-radius:1rem;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);
      box-shadow:0 12px 28px rgba(0,0,0,.06);
    }
    .quick-card .card-body{ padding:1.5rem; }
    .quick-item{
      display:flex; align-items:center; gap:.6rem; padding:.75rem .85rem;
      border-radius:.75rem; background:#fff; box-shadow:0 6px 14px rgba(0,0,0,.04);
      text-decoration:none; color:#333; font-weight:600; font-size:.95rem;
      transition:.2s;
    }
    .quick-item:hover{ background:#f4efff; transform:translateY(-2px); }
    .quick-item .bi{ font-size:1.1rem; color:#6f42c1; }

    /* Recent table */
    .table-modern{
      --row-shadow: 0 6px 18px rgba(0,0,0,.05);
      border-collapse:separate; border-spacing:0 10px; width:100%;
    }
    .table-modern thead th{
      position:sticky; top:0; z-index:1; background:#f6f4ff; color:#5a5a7a;
      font-weight:700; border:none; padding:.75rem .9rem; text-transform:uppercase;
      font-size:.75rem; letter-spacing:.04em; border-top-left-radius:.75rem; border-top-right-radius:.75rem;
    }
    .table-modern tbody tr{ background:#fff; box-shadow:var(--row-shadow); }
    .table-modern tbody td{ border-top:none; border-bottom:none; vertical-align:middle; padding:.85rem .9rem; }
    .table-modern tbody tr:hover{ transform:translateY(-2px); box-shadow:0 12px 28px rgba(111,66,193,.12) }
    .status-chip{ display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.3rem .6rem; font-weight:700; font-size:.78rem; }
    .chip-pending{ color:#664d03; background:#fff3cd; border:1px solid #ffecb5; }
    .chip-approved{ color:#0f5132; background:#d1e7dd; border:1px solid #badbcc; }
    .chip-rejected{ color:#842029; background:#f8d7da; border:1px solid #f5c2c7; }

    .empty-block{
      border-radius:1rem; padding:1.25rem; text-align:center;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%); box-shadow:0 14px 34px rgba(111,66,193,.12)
    }
    .empty-block .bi{ font-size:1.6rem; color:#6f42c1; }

    .tip-card{ border:0; border-radius:1rem; box-shadow:0 12px 28px rgba(0,0,0,.06); }
    .mini-img{ border-radius:.8rem; object-fit:cover; width:100%; height:120px; }
  </style>

  <!-- HERO -->
  <section class="hero-ngo mb-4">
    <img src="assets/images/herongo.webp" alt="NGO dashboard">
    <div class="mask"></div>
    <div class="content">
      <div class="d-flex flex-wrap gap-2 mb-2">
        <span class="hero-badge"><i class="bi bi-shield-check"></i> Verified Organization</span>
        <span class="hero-badge"><i class="bi bi-clipboard2-check"></i> Manage Requests</span>
        <span class="hero-badge"><i class="bi bi-rocket-takeoff"></i> Grow Adoptions</span>
      </div>
      <h2 class="fw-bold mb-1">Welcome back, Partner!</h2>
      <p class="mb-0">Keep your pet listings fresh, respond to adopters quickly, and help more animals find homes.</p>
      <div class="mt-3 d-flex flex-wrap gap-2">
        <a href="ngo/pets" class="btn btn-light btn-sm"><i class="bi bi-paw-fill me-1"></i> Manage Pets</a>
        <a href="ngo/requests" class="btn btn-outline-light btn-sm"><i class="bi bi-inbox me-1"></i> View Requests</a>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="mb-3">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="card stat-card">
          <div class="card-body d-flex align-items-center gap-3">
            <!-- icon swapped to more reliable paw -->
            <div class="stat-icon"><i class="bi bi-check-lg"></i></div>
            <div>
              <div class="text-muted small">Available</div>
              <div class="stat-value"><?= (int)($stats['available'] ?? 0) ?></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card stat-card">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon green"><i class="bi bi-heart-fill"></i></div>
            <div>
              <div class="text-muted small">Adopted</div>
              <div class="stat-value"><?= (int)($stats['adopted'] ?? 0) ?></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card stat-card">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon gray"><i class="bi bi-slash-circle"></i></div>
            <div>
              <div class="text-muted small">Inactive</div>
              <div class="stat-value"><?= (int)($stats['inactive'] ?? 0) ?></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card stat-card">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon blue"><i class="bi bi-hourglass-split"></i></div>
            <div>
              <div class="text-muted small">Pending Requests</div>
              <div class="stat-value"><?= (int)$pendingReqCount ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- QUICK ACTIONS + FEATURE -->
  <section class="mb-4">
    <div class="row g-3">
      <div class="col-12 col-lg-8">
        <!-- h-100 to balance with Tips -->
        <div class="card quick-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="mb-0">Quick Actions</h5>
              <a href="ngo/pets" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Add Pet</a>
            </div>
            <div class="row g-3">
              <div class="col-12 col-sm-6 col-xl-4">
                <a class="quick-item" href="ngo/pets">
                  <i class="bi bi-card-image"></i>
                  <span>Manage listings</span>
                </a>
              </div>
              <div class="col-12 col-sm-6 col-xl-4">
                <a class="quick-item" href="ngo/requests">
                  <i class="bi bi-clipboard2-check"></i>
                  <span>Review new requests</span>
                </a>
              </div>
              <div class="col-12 col-sm-6 col-xl-4">
                <a class="quick-item" href="ngo/requests?status=approved">
                  <i class="bi bi-check2-circle"></i>
                  <span>Approved Applications</span>
                </a>
              </div>
              <div class="col-12 col-sm-6 col-xl-4">
                <a class="quick-item" href="ngo/requests?status=pending">
                  <i class="bi bi-hourglass-split"></i>
                  <span>Pending Applications</span>
                </a>
              </div>
              <div class="col-12 col-sm-6 col-xl-4">
                <a class="quick-item" href="ngo/pets?status=inactive">
                  <i class="bi bi-archive"></i>
                  <span>Inactive pets</span>
                </a>
              </div>
              <div class="col-12 col-sm-6 col-xl-4">
                <a class="quick-item" href="ngo/about">
                  <i class="bi bi-info-circle"></i>
                  <span>About platform</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /left -->

      <div class="col-12 col-lg-4">
        <!-- h-100 to balance heights -->
        <div class="card tip-card h-100">
          <div class="card-body">
            <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb me-1 text-warning"></i> Tips to boost adoptions</h6>
            <div class="row g-2">
              <div class="col-5"><img src="assets/images/feature1ngo.jpeg" class="mini-img" alt="Tip 1"></div>
              <div class="col-7 small text-muted d-flex align-items-center">Add clear outdoor photos and a short, friendly bio.</div>

              <div class="col-5"><img src="assets/images/feature2ngo.jpg" class="mini-img" alt="Tip 2"></div>
              <div class="col-7 small text-muted d-flex align-items-center">Keep vaccination info up-to-date—adopters filter for it.</div>

              <div class="col-5"><img src="assets/images/feature3ngo.jpg" class="mini-img" alt="Tip 3"></div>
              <div class="col-7 small text-muted d-flex align-items-center">Respond to pending requests within 24–48 hours.</div>
            </div>
          </div>
        </div>
      </div><!-- /right -->
    </div>
  </section>

  <!-- RECENT REQUESTS -->
  <section class="mb-1">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h5 class="mb-0">Recent Requests</h5>
      <a class="btn btn-sm btn-outline-primary" href="ngo/requests"><i class="bi bi-view-list me-1"></i>View all</a>
    </div>

    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-modern align-middle mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Pet</th>
                <th>Applicant</th>
                <th>Status</th>
                <th>Created</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentRequests as $r): ?>
                <?php
                  $status = strtolower($r['status']);
                  $chip  = $status==='pending' ? 'chip-pending' : ($status==='approved' ? 'chip-approved' : 'chip-rejected');
                  $icon  = $status==='pending' ? 'bi-hourglass-split' : ($status==='approved' ? 'bi-check-circle' : 'bi-x-circle');
                ?>
                <tr>
                  <td>#<?= (int)$r['request_id'] ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($r['pet_name']) ?></td>
                  <td><?= htmlspecialchars($r['user_name']) ?></td>
                  <td>
                    <span class="status-chip <?= $chip ?>"><i class="bi <?= $icon ?>"></i> <?= htmlspecialchars(ucfirst($r['status'])) ?></span>
                  </td>
                  <td class="text-muted small"><?= htmlspecialchars($r['created_at']) ?></td>
                  <td class="text-center text-nowrap">
                    <a href="ngo/request?id=<?= (int)$r['request_id'] ?>" class="btn btn-sm btn-primary">
                      <i class="bi bi-eye me-1"></i> View
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>

              <?php if (empty($recentRequests)): ?>
                <tr>
                  <td colspan="6">
                    <div class="empty-block">
                      <i class="bi bi-paw-fill"></i>
                      <div class="fw-bold mt-1">No recent requests</div>
                      <div class="text-muted small">You’ll see new adoption requests here as they arrive.</div>
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
