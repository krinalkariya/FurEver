<div class="dashboard-wrap py-4">

  <style>
    /* Scoped to dashboard */
    .hero {
      background:
        radial-gradient(1200px 400px at 90% -10%, rgba(111,66,193,.12) 0%, rgba(111,66,193,0) 60%),
        linear-gradient(135deg, #fdfbff 0%, #f6f4ff 60%, #eff6ff 100%);
      border-radius: 1rem;
      padding: 2rem 1.25rem;
      position: relative;
      overflow: hidden; /* keep SVG inside */
      box-shadow: 0 16px 40px rgba(111,66,193,.12);
    }
    .hero .badge-pill {
      background: rgba(255,255,255,.7);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(111,66,193,.2);
      border-radius: 999px;
      padding: .4rem .75rem;
      font-weight: 600;
    }
    .hero-illustr {
      position: absolute; right: -10px; bottom: -10px; opacity: .22; width: 280px; max-width: 45%;
      transform: rotate(-8deg);
      pointer-events: none; user-select: none;
    }

    /* Stats */
    .stat-card {
      border: 0; border-radius: 1rem;
      box-shadow: 0 12px 28px rgba(13,110,253,.08);
      background: #fff;
    }
    .stat-icon {
      width: 44px; height: 44px; border-radius: 12px;
      display: grid; place-items: center;
      background: linear-gradient(135deg, #6f42c1, #8f62ff);
      color: #fff; font-size: 1.25rem;
      box-shadow: 0 8px 20px rgba(111,66,193,.3);
    }
    .stat-icon.blue { background: linear-gradient(135deg,#0d6efd,#6ea8fe); }

    /* Filters */
    .filters { position: relative; z-index: 2; margin-top: .25rem; }
    .chip{display:inline-flex;align-items:center;gap:.45rem;border:1.5px solid rgba(111,66,193,.3);background:#fff;border-radius:999px;padding:.45rem .85rem;font-weight:600;font-size:.92rem;text-decoration:none;transition:.15s;box-shadow:0 8px 18px rgba(111,66,193,.08)}
    .chip:hover{background:#f4efff;border-color:#8f62ff;box-shadow:0 14px 28px rgba(111,66,193,.18)}
    .chip.active{background:#6f42c1;color:#fff;border-color:#6f42c1;box-shadow:0 10px 26px rgba(111,66,193,.35)}
    .chip .bi{opacity:.9}
    .filter-bar{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem}
    .scroll-hints { white-space: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: .25rem; }

    /* Pet cards – bolder visuals */
    .pet-card {
      border: 0; border-radius: 1rem; overflow: hidden;
      transition: transform .18s ease, box-shadow .18s ease;
      box-shadow: 0 12px 32px rgba(0,0,0,.07);
      background: #fff; height: 100%; position: relative;
    }
    .pet-card:hover { transform: translateY(-4px); box-shadow: 0 24px 48px rgba(111,66,193,.18); }
    .pet-photo-wrap { position: relative; }
    .pet-media {
      aspect-ratio: 4/3; width: 100%; object-fit: cover; display: block;
      transform: scale(1.01);
    }
    .pet-gradient {
      position:absolute; inset:0;
      background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,.55) 100%);
      opacity:.65;
    }
    .pet-badge {
      position: absolute; top: .6rem; left: .6rem;
      background: rgba(0,0,0,.58); color: #fff; font-size: .8rem;
      border-radius: .5rem; padding: .2rem .5rem;
      backdrop-filter: blur(2px);
    }
    .pet-age {
      position: absolute; top: .6rem; right: .6rem;
      background: #fff; color:#111; font-size: .8rem; font-weight:700;
      border-radius: .5rem; padding: .2rem .5rem; box-shadow: 0 8px 18px rgba(0,0,0,.15);
    }
    .pet-title { margin-top:.4rem; font-weight: 700; font-size: 1.05rem; }
    .pet-meta { color: #6c757d; font-size: .95rem; }
    .pet-footer { display:flex; align-items:center; justify-content:space-between; gap:.5rem; margin-top:.8rem; }
    .pet-tag {
      font-size: .75rem; border:1px solid rgba(111,66,193,.25); border-radius:999px; padding:.15rem .5rem; color:#6f42c1;
      background:#f8f3ff; white-space:nowrap;
    }

    /* Featured photos gallery */
    .photo-tile {
      position: relative; border-radius:1rem; overflow:hidden;
      box-shadow: 0 14px 34px rgba(0,0,0,.09);
      transition: transform .2s ease, box-shadow .2s ease;
      background:#eee;
    }
    .photo-tile img { width:100%; height: 220px; object-fit: cover; display:block; }
    .photo-tile:hover { transform: translateY(-4px); box-shadow: 0 24px 54px rgba(111,66,193,.18); }
    .photo-cap{
      position:absolute;
      left:.75rem; 
      bottom:.65rem;
      max-width: calc(100% - 1.5rem);
      color:#fff;
      font-weight:700;
      line-height:1.2;
      padding:.45rem .75rem;
      border-radius:.75rem;
      /* glassy dark pill for contrast on any image */
      background: linear-gradient(180deg, rgba(15,15,20,.55), rgba(15,15,20,.65));
      border:1px solid rgba(255,255,255,.25);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      box-shadow: 0 6px 18px rgba(0,0,0,.28);
      text-shadow: 0 1px 2px rgba(0,0,0,.35);
      /* truncate gracefully if text gets long */
      overflow:hidden;
      text-overflow:ellipsis;
      white-space:nowrap;
    }

    @media (max-width: 576px){
      .photo-cap{ 
        font-size:.95rem; 
        padding:.35rem .6rem; 
        border-radius:.65rem; 
      }
    }

    /* How it works cards */
    .how-card {
      border:0; border-radius:1rem; background:#fff; height:100%;
      box-shadow: 0 12px 28px rgba(0,0,0,.06);
      transition: transform .18s ease, box-shadow .18s ease;
    }
    .how-card:hover { transform: translateY(-3px); box-shadow: 0 24px 48px rgba(111,66,193,.16); }
    .how-icon {
      width: 52px; height: 52px; border-radius: 14px; display:grid; place-items:center;
      background: linear-gradient(135deg,#6f42c1,#8f62ff); color:#fff; font-size:1.2rem;
      box-shadow: 0 10px 24px rgba(111,66,193,.28);
    }

    .fs-095 { font-size:.95rem; }
 .empty-state{
    text-align:center;
    border:0;
    border-radius:1rem;
    padding:2rem 1rem;
    background: linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);
    box-shadow: 0 14px 34px rgba(111,66,193,.12);
  }
  .empty-state .empty-icon{
    font-size:2.2rem;
    color:#6f42c1;
    margin-bottom:.5rem;
  }
  .empty-state h6{margin:0;font-weight:700}
  .empty-state p{margin:.25rem 0 1rem;color:#6c757d}
  </style>

  <!-- HERO -->
  <section class="hero mb-4">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="d-flex gap-2 mb-2">
          <span class="badge-pill"><i class="bi bi-patch-check-fill me-1"></i>Verified NGOs</span>
          <span class="badge-pill"><i class="bi bi-shield-heart me-1"></i>Safe & Transparent</span>
          <span class="badge-pill"><i class="bi bi-bell me-1"></i>Real-time Status</span>
        </div>
        <h2 class="fw-bold display-6 mb-2">Find your <span class="text-primary">fur-ever</span> friend</h2>
        <p class="lead mb-3">Browse loving dogs and cats from verified NGOs. Apply in seconds and track your request—simple, modern, and humane.</p>

        <div class="d-flex flex-wrap gap-2">
          <a href="user/pets" class="btn btn-primary">
            <i class="bi bi-search-heart me-1"></i> Browse Pets
          </a>
          <a href="user/requests" class="btn btn-outline-primary">
            <i class="bi bi-inbox me-1"></i> My Requests
          </a>
          <a href="user/profile" class="btn btn-outline-secondary">
            <i class="bi bi-person-badge me-1"></i> Your Profile
          </a>
        </div>
      </div>
      <div class="col-lg-4 d-none d-lg-block">
        <!-- Decorative paw SVG -->
        <svg class="hero-illustr" viewBox="0 0 200 200" fill="none">
          <g fill="#6f42c1">
            <circle cx="45" cy="60" r="18" opacity=".22"/>
            <circle cx="80" cy="40" r="16" opacity=".22"/>
            <circle cx="115" cy="48" r="14" opacity=".22"/>
            <circle cx="150" cy="70" r="16" opacity=".22"/>
            <path d="M96 80c30-6 64 20 58 46-3 12-20 20-36 22-17 2-36-1-46-12s-8-30 2-40c6-6 12-12 22-16z" opacity=".22"/>
          </g>
        </svg>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="mb-4">
    <div class="row g-3">
      <div class="col-12 col-md-6">
        <div class="card stat-card">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon"><i class="bi bi-heart-fill"></i></div>
            <div>
              <div class="text-muted small">Available Pets</div>
              <div class="h3 mb-0"><?= (int)$totalAvailable; ?></div>
            </div>
            <div class="ms-auto">
              <a class="btn btn-sm btn-outline-primary" href="user/pets">Explore</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="card stat-card">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon blue"><i class="bi bi-clipboard-check-fill"></i></div>
            <div>
              <div class="text-muted small">Your Active Requests</div>
              <div class="h3 mb-0"><?= (int)$activeRequests; ?></div>
            </div>
            <div class="ms-auto">
              <a class="btn btn-sm btn-outline-primary" href="user/requests">View</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- QUICK FILTER CHIPS -->
  <?php
    // Build active-state chips that preserve multiple filters
    $filters = [
      'species'    => $_GET['species']    ?? '',
      'ageband'    => $_GET['ageband']    ?? '',
      'vaccinated' => $_GET['vaccinated'] ?? '',
      'breed'      => $_GET['breed']      ?? '',
      'city'       => $_GET['city']       ?? '',
    ];
    $qs = function(array $overrides = []) use ($filters) {
        $next = array_merge($filters, $overrides);
        // drop empty keys
        $next = array_filter($next, fn($v) => $v !== '' && $v !== null);
        return 'user/dashboard' . (empty($next) ? '' : ('?' . http_build_query($next)));
    };
    $is = function(string $key, string $val) use ($filters) {
        return isset($filters[$key]) && $filters[$key] === $val;
    };
    $anyActive = !empty(array_filter($filters, fn($v)=>$v!=='' && $v!==null));
  ?>

  <section class="mb-4 filters">
    <div class="filter-bar">
      <!-- Species -->
      <a class="chip <?= $is('species','dog')?'active':'' ?>" href="<?= htmlspecialchars($qs(['species'=>'dog'])) ?>">
        <i class="bi bi-emoji-smile"></i> Dogs
      </a>
      <a class="chip <?= $is('species','cat')?'active':'' ?>" href="<?= htmlspecialchars($qs(['species'=>'cat'])) ?>">
        <i class="bi bi-emoji-heart-eyes"></i> Cats
      </a>

      <!-- Age bands -->
      <a class="chip <?= $is('ageband','puppy')?'active':'' ?>" href="<?= htmlspecialchars($qs(['ageband'=>'puppy'])) ?>">
        <i class="bi bi-stars"></i> Puppies/Kittens
      </a>
      <a class="chip <?= $is('ageband','young')?'active':'' ?>" href="<?= htmlspecialchars($qs(['ageband'=>'young'])) ?>">
        <i class="bi bi-sun"></i> 2–6 yrs
      </a>
      <a class="chip <?= $is('ageband','senior')?'active':'' ?>" href="<?= htmlspecialchars($qs(['ageband'=>'senior'])) ?>">
        <i class="bi bi-moon-stars"></i> Senior
      </a>

      <!-- Vaccinated -->
      <a class="chip <?= $is('vaccinated','yes')?'active':'' ?>" href="<?= htmlspecialchars($qs(['vaccinated'=>'yes'])) ?>">
        <i class="bi bi-shield-check"></i> Vaccinated
      </a>

      <!-- Clear button -->
      <?php if ($anyActive): ?>
        <a class="chip" href="user/dashboard">
          <i class="bi bi-x-circle"></i> Clear
        </a>
      <?php endif; ?>
    </div>
  </section>

  <!-- RECENTLY ADDED -->
  <section class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h5 class="mb-0">Recently Added</h5>
      <a class="btn btn-sm btn-outline-primary" href="user/pets"><i class="bi bi-view-list me-1"></i>See all</a>
    </div>

    <div class="row g-3">
      <?php foreach ($recentPets as $p): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card pet-card h-100">
            <div class="pet-photo-wrap">
              <img src="<?= htmlspecialchars($p['image']) ?>" class="pet-media" alt="pet">
              <div class="pet-gradient"></div>
              <span class="pet-badge"><?= htmlspecialchars(ucfirst($p['species'])) ?></span>
              <span class="pet-age"><?= (int)$p['age'] ?>y</span>
            </div>
            <div class="card-body">
              <div class="pet-title"><?= htmlspecialchars($p['name']) ?></div>
              <div class="pet-meta fs-095">
                <?= htmlspecialchars($p['breed']) ?>
                <?php if (!empty($p['city'])): ?>
                  • <?= htmlspecialchars($p['city']) ?>
                <?php endif; ?>
              </div>
              <div class="pet-footer">
                <span class="pet-tag">
                  <i class="bi bi-shield-check me-1"></i>
                  <?= ((isset($p['vaccinated']) ? $p['vaccinated'] : null) === 'yes') ? 'Vaccinated' : 'Health Checked' ?>
                </span>
                <a class="btn btn-sm btn-primary" href="user/pet?id=<?= (int)$p['pet_id'] ?>">
                  <i class="bi bi-eye me-1"></i> View
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if (empty($recentPets)): ?>
       <div class="empty-state">
  <div class="empty-icon"><i class="bi bi-paw-fill"></i></div>
  <h6>No pets found</h6>
  <p>New friends arrive often — try browsing all pets.</p>
  <a class="btn btn-primary" href="user/pets">
    <i class="bi bi-search-heart me-1"></i> Browse all pets
  </a>
</div>
      <?php endif; ?>
    </div>
  </section>

  <!-- FEATURED PHOTOS (demo paths updated) -->
  <section class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h5 class="mb-0">Featured Moments</h5>
      <span class="text-muted small">Photos from recent adoptions</span>
    </div>
    <div class="row g-3">
      <div class="col-12 col-md-6 col-lg-4">
        <div class="photo-tile">
          <img src="assets/images/feature1.jpg" alt="adoption">
          <div class="photo-cap">Happy Tails</div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="photo-tile">
          <img src="assets/images/feature2.jpg" alt="adoption">
          <div class="photo-cap">New Beginnings</div>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="photo-tile">
          <img src="assets/images/feature3.jpg" alt="adoption">
          <div class="photo-cap">Best Friends</div>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="mb-2">
    <h5 class="mb-3">How it works</h5>
    <div class="row g-3">
      <div class="col-12 col-md-4">
        <div class="card how-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-2">
              <div class="how-icon"><i class="bi bi-search-heart"></i></div>
              <div class="fw-bold">Browse & Filter</div>
            </div>
            <p class="mb-0 text-muted fs-095">Explore dogs and cats. Use species, age, and vaccination filters to narrow down to your perfect match.</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card how-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-2">
              <div class="how-icon"><i class="bi bi-clipboard-plus"></i></div>
              <div class="fw-bold">Apply in Seconds</div>
            </div>
            <p class="mb-0 text-muted fs-095">Submit an adoption request. Your application goes directly to the NGO that listed the pet.</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card how-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-2">
              <div class="how-icon"><i class="bi bi-bell"></i></div>
              <div class="fw-bold">Track Status</div>
            </div>
            <p class="mb-0 text-muted fs-095">Check updates anytime under “My Requests” and connect when you’re approved.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
