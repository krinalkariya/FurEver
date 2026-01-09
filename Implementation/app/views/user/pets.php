<div class="browse-wrap py-3">

  <style>
    /* Reuse dashboard look */
    .chip{display:inline-flex;align-items:center;gap:.45rem;border:1.5px solid rgba(111,66,193,.3);background:#fff;border-radius:999px;padding:.45rem .85rem;font-weight:600;font-size:.92rem;text-decoration:none;transition:.15s;box-shadow:0 8px 18px rgba(111,66,193,.08)}
    .chip:hover{background:#f4efff;border-color:#8f62ff;box-shadow:0 14px 28px rgba(111,66,193,.18)}
    .chip.active{background:#6f42c1;color:#fff;border-color:#6f42c1;box-shadow:0 10px 26px rgba(111,66,193,.35)}
    .chip .bi{opacity:.9}
    .filter-bar{display:flex;flex-wrap:wrap;gap:.5rem;margin: .25rem 0 1rem}

    .pet-card{border:0;border-radius:1rem;overflow:hidden;transition:transform .18s ease,box-shadow .18s ease;box-shadow:0 12px 32px rgba(0,0,0,.07);background:#fff;height:100%;position:relative}
    .pet-card:hover{transform:translateY(-4px);box-shadow:0 24px 48px rgba(111,66,193,.18)}
    .pet-photo-wrap{position:relative}
    .pet-media{aspect-ratio:4/3;width:100%;object-fit:cover;display:block;transform:scale(1.01)}
    .pet-gradient{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0) 50%,rgba(0,0,0,.55) 100%);opacity:.65}
    .pet-badge{position:absolute;top:.6rem;left:.6rem;background:rgba(0,0,0,.58);color:#fff;font-size:.8rem;border-radius:.5rem;padding:.2rem .5rem;backdrop-filter:blur(2px)}
    .pet-age{position:absolute;top:.6rem;right:.6rem;background:#fff;color:#111;font-size:.8rem;font-weight:700;border-radius:.5rem;padding:.2rem .5rem;box-shadow:0 8px 18px rgba(0,0,0,.15)}
    .pet-title{margin-top:.4rem;font-weight:700;font-size:1.05rem}
    .pet-meta{color:#6c757d;font-size:.95rem}
    .pet-footer{display:flex;align-items:center;justify-content:space-between;gap:.5rem;margin-top:.8rem}
    .pet-tag{font-size:.75rem;border:1px solid rgba(111,66,193,.25);border-radius:999px;padding:.15rem .5rem;color:#6f42c1;background:#f8f3ff;white-space:nowrap}

    .empty-state{text-align:center;border:0;border-radius:1rem;padding:2rem 1rem;background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);box-shadow:0 14px 34px rgba(111,66,193,.12)}
    .empty-state .empty-icon{font-size:2.2rem;color:#6f42c1;margin-bottom:.5rem}
    .empty-state h6{margin:0;font-weight:700}
    .empty-state p{margin:.25rem 0 1rem;color:#6c757d}

    .inline-form .form-control,.inline-form .form-select{border-radius:.75rem}
  </style>

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
    <h2 class="mb-0">Browse Pets</h2>

    <!-- Optional: compact "More filters" toggler -->
    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#moreFilters" aria-expanded="false" aria-controls="moreFilters">
      <i class="bi bi-sliders me-1"></i> More filters
    </button>
  </div>

  <?php
    // Build chip URLs preserving multiple filters
    $filters = $filters ?? ['species'=>'','breed'=>'','city'=>'','ageband'=>'','vaccinated'=>''];
    $qs = function(array $overrides = []) use ($filters) {
        $next = array_merge($filters, $overrides);
        // drop empties
        $next = array_filter($next, fn($v)=>$v!=='' && $v!==null);
        return 'user/pets' . (empty($next) ? '' : ('?' . http_build_query($next)));
    };
    $is = function(string $key, string $val) use ($filters) {
        return isset($filters[$key]) && $filters[$key] === $val;
    };
    $anyActive = !empty(array_filter($filters, fn($v)=>$v!=='' && $v!==null));
  ?>

  <!-- Chip filters (multi-select via query params) -->
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
      <i class="bi bi-sun"></i> 1–3 yrs
    </a>
    <a class="chip <?= $is('ageband','adult')?'active':'' ?>" href="<?= htmlspecialchars($qs(['ageband'=>'adult'])) ?>">
      <i class="bi bi-brightness-alt-high"></i> 3–7 yrs
    </a>
    <a class="chip <?= $is('ageband','senior')?'active':'' ?>" href="<?= htmlspecialchars($qs(['ageband'=>'senior'])) ?>">
      <i class="bi bi-moon-stars"></i> Senior
    </a>

    <!-- Vaccinated -->
    <a class="chip <?= $is('vaccinated','yes')?'active':'' ?>" href="<?= htmlspecialchars($qs(['vaccinated'=>'yes'])) ?>">
      <i class="bi bi-shield-check"></i> Vaccinated
    </a>

    <!-- Clear -->
    <?php if ($anyActive): ?>
      <a class="chip" href="user/pets"><i class="bi bi-x-circle"></i> Clear</a>
    <?php endif; ?>
  </div>

  <!-- More filters (Breed/City) keep GET semantics; minimal UI -->
  <div id="moreFilters" class="collapse">
    <form method="GET" class="inline-form row g-2 mb-3">
      <div class="col-12 col-md-3">
        <input type="text" name="breed" placeholder="Breed" value="<?= htmlspecialchars($filters['breed'] ?? '') ?>" class="form-control">
      </div>
      <div class="col-12 col-md-3">
        <input type="text" name="city" placeholder="City" value="<?= htmlspecialchars($filters['city'] ?? '') ?>" class="form-control">
      </div>
      <!-- Preserve existing selections when submitting -->
      <input type="hidden" name="species" value="<?= htmlspecialchars($filters['species'] ?? '') ?>">
      <input type="hidden" name="ageband" value="<?= htmlspecialchars($filters['ageband'] ?? '') ?>">
      <input type="hidden" name="vaccinated" value="<?= htmlspecialchars($filters['vaccinated'] ?? '') ?>">

      <div class="col-6 col-md-auto">
        <button class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Apply</button>
      </div>
      <div class="col-6 col-md-auto">
        <a href="user/pets" class="btn btn-outline-secondary w-100">Reset</a>
      </div>
    </form>
  </div>

  <?php if (empty($pets)): ?>
    <!-- Themed empty state -->
    <div class="empty-state">
      <div class="empty-icon"><i class="bi bi-paw-fill"></i></div>
      <h6>No pets match your filters</h6>
      <p>Try broadening your search or browse everything.</p>
      <a class="btn btn-primary" href="user/pets">
        <i class="bi bi-search-heart me-1"></i> Browse all pets
      </a>
    </div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($pets as $p): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card pet-card h-100">
            <div class="pet-photo-wrap">
              <?php if (!empty($p['image'])): ?>
                <img src="<?= htmlspecialchars($p['image']) ?>" class="pet-media" alt="pet">
              <?php else: ?>
                <img src="assets/images/placeholder-pet.jpg" class="pet-media" alt="pet">
              <?php endif; ?>
              <div class="pet-gradient"></div>
              <span class="pet-badge"><?= htmlspecialchars(ucfirst($p['species'])) ?></span>
              <span class="pet-age"><?= (int)$p['age'] ?>y</span>
            </div>
            <div class="card-body">
              <div class="pet-title"><?= htmlspecialchars($p['name']) ?></div>
              <div class="pet-meta">
                <?= htmlspecialchars($p['breed']) ?>
                <?php if (!empty($p['sex'])): ?> • <?= htmlspecialchars(ucfirst($p['sex'])) ?><?php endif; ?>
                <?php if (!empty($p['city'])): ?> • <?= htmlspecialchars($p['city']) ?><?php endif; ?>
              </div>
              <div class="pet-footer">
                <span class="pet-tag">
                  <i class="bi bi-hospital me-1"></i>
                  <?= (isset($p['vaccinated']) && $p['vaccinated']==='yes') ? 'Vaccinated' : 'Health Checked' ?>
                </span>
                <a href="user/pet?id=<?= (int)$p['pet_id'] ?>" class="btn btn-sm btn-primary">
                  <i class="bi bi-eye me-1"></i> View
                </a>
              </div>
              <?php if (!empty($p['ngo_name'])): ?>
                <div class="text-muted small mt-2">
                  <i class="bi bi-building me-1"></i><?= htmlspecialchars($p['ngo_name']) ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>
