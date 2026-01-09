<div class="admin-ngo-show py-2">

  <style>
    .ngo-header-card{
      border-radius:1rem; border:0;
      box-shadow:0 12px 28px rgba(0,0,0,.06);
    }
    .ngo-header-card h5{ font-weight:700; }

    .status-chip{
      display:inline-flex; align-items:center; gap:.35rem;
      border-radius:999px; padding:.3rem .7rem;
      font-weight:600; font-size:.8rem; border:1px solid transparent;
    }
    .chip-approved{ color:#0f5132; background:#d1e7dd; border-color:#badbcc; }
    .chip-rejected{ color:#842029; background:#f8d7da; border-color:#f5c2c7; }
    .chip-pending{ color:#664d03; background:#fff3cd; border-color:#ffecb5; }

    /* Table */
    .table-modern thead th{
      font-size:.8rem; text-transform:uppercase; letter-spacing:.03em;
      color:#555; font-weight:600; border-bottom:2px solid #eee;
      padding:.7rem .8rem;
    }
    .table-modern tbody td{
      padding:.8rem; vertical-align:middle;
    }
    .table-modern tbody tr:hover{
      background:#faf9ff;
    }
    .pet-thumb{ width:60px; height:60px; object-fit:cover; border-radius:.5rem; }
  </style>

  <!-- NGO Info -->
  <div class="card ngo-header-card mb-3">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-md-6">
          <h5 class="mb-1"><?= htmlspecialchars($ngo['name']) ?></h5>
          <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($ngo['city']) ?></div>
          <div class="text-muted small"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($ngo['email']) ?></div>
          <div class="text-muted small"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($ngo['phone']) ?></div>
        </div>
        <div class="col-md-6 text-md-end">
          <?php
            $s = strtolower($ngo['status']);
            $chip = $s==='approved' ? 'chip-approved' : ($s==='rejected' ? 'chip-rejected' : 'chip-pending');
          ?>
          <div class="mb-1">
            <span class="status-chip <?= $chip ?>"><?= htmlspecialchars(ucfirst($ngo['status'])) ?></span>
          </div>
          <?php if (!empty($ngo['status_reason'])): ?>
            <div class="small text-muted">Reason: <?= htmlspecialchars($ngo['status_reason']) ?></div>
          <?php endif; ?>
          <div class="small text-muted">Joined: <?= htmlspecialchars($ngo['created_at']) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pets by NGO -->
  <h5 class="mb-2">Pets by this NGO</h5>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-modern mb-0 align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Pet</th>
            <th>Name / Species</th>
            <th>Breed / Sex</th>
            <th>Age</th>
            <th>Vaccinated</th>
            <th>Status</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pets as $p): ?>
            <?php
              $vChip = $p['vaccinated']==='yes' ? 'chip-approved' : 'chip-rejected';
              $st = strtolower($p['status']);
              $sChip = $st==='available' ? 'chip-pending' : ($st==='adopted' ? 'chip-approved' : 'chip-rejected');
            ?>
            <tr>
              <td class="fw-semibold">#<?= (int)$p['pet_id'] ?></td>
              <td>
                <?php if (!empty($p['image'])): ?>
                  <img src="<?= htmlspecialchars($p['image']) ?>" alt="" class="pet-thumb">
                <?php else: ?>
                  <span class="text-muted small">—</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                <small class="text-muted"><?= htmlspecialchars($p['species']) ?></small>
              </td>
              <td>
                <div><?= htmlspecialchars($p['breed']) ?></div>
                <small class="text-muted"><?= htmlspecialchars($p['sex']) ?></small>
              </td>
              <td><?= (int)$p['age'] ?> yrs</td>
              <td><span class="status-chip <?= $vChip ?>"><?= ucfirst($p['vaccinated']) ?></span></td>
              <td><span class="status-chip <?= $sChip ?>"><?= ucfirst($p['status']) ?></span></td>
              <td class="small text-muted"><?= htmlspecialchars($p['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($pets)): ?>
            <tr>
              <td colspan="8" class="text-center py-4 text-muted">No pets for this NGO.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>