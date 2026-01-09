<?php $active='pets'; ?>
<div class="admin-pets-list py-2">

  <style>
    /* Scoped, clean table (aligned with NGOs/Users lists) */
    .table-pets { border-collapse: separate; border-spacing: 0; width:100%; }
    .table-pets thead th{
      font-weight:600; font-size:.85rem; text-transform:uppercase; letter-spacing:.03em;
      padding:.75rem .9rem; border-bottom:2px solid #e9ecef; color:#495057; white-space:nowrap;
    }
    .table-pets tbody td{
      padding:.9rem; vertical-align:middle; border-top:1px solid #f0f0f0;
    }
    .table-pets tbody tr:hover{ background:#faf9ff; }

    .pet-thumb{ width:60px; height:60px; object-fit:cover; border-radius:.5rem; display:block; }

    /* Chips (consistent across admin pages) */
    .chip{
      display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.32rem .65rem;
      font-weight:600; font-size:.78rem; border:1px solid transparent; white-space:nowrap;
    }
    .chip-yes{ color:#0f5132; background:#d1e7dd; border-color:#badbcc; }
    .chip-no{ color:#41464b; background:#e2e3e5; border-color:#d3d6d8; }
    .chip-available{ color:#055160; background:#cff4fc; border-color:#b6effb; }
    .chip-adopted{ color:#0f5132; background:#d1e7dd; border-color:#badbcc; }
    .chip-inactive{ color:#664d03; background:#fff3cd; border-color:#ffecb5; }

    .name-strong{ font-weight:600; }
    .muted{ color:#6c757d; }

    .empty-block{
      border-radius:1rem; padding:1.25rem; text-align:center;
      background:#f8f9fa; border:1px dashed #ddd;
    }
    .empty-block .bi{ font-size:1.4rem; color:#6f42c1; }
  </style>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-pets align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Species / Breed</th>
            <th>Sex / Age</th>
            <th>Vaccinated</th>
            <th>Status</th>
            <th>NGO</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pets as $p): ?>
            <?php
              $pid   = (int)$p['pet_id'];
              $img   = $p['image'] ?? '';
              $vchip = ($p['vaccinated']==='yes') ? 'chip-yes' : 'chip-no';
              $st    = strtolower($p['status']);
              $schip = $st==='available' ? 'chip-available' : ($st==='adopted' ? 'chip-adopted' : 'chip-inactive');
            ?>
            <tr>
              <td class="fw-bold">#<?= $pid ?></td>
              <td style="width:72px">
                <?php if (!empty($img)): ?>
                  <img src="<?= htmlspecialchars($img) ?>" alt="" class="pet-thumb">
                <?php else: ?>
                  <span class="muted small">—</span>
                <?php endif; ?>
              </td>
              <td class="name-strong"><?= htmlspecialchars($p['name']) ?></td>
              <td>
                <?= htmlspecialchars(ucfirst($p['species'])) ?> /
                <span class="muted"><?= htmlspecialchars($p['breed']) ?></span>
              </td>
              <td>
                <?= htmlspecialchars(ucfirst($p['sex'])) ?> /
                <span class="muted"><?= (int)$p['age'] ?> yrs</span>
              </td>
              <td>
                <span class="chip <?= $vchip ?>"><?= ($p['vaccinated']==='yes'?'Yes':'No') ?></span>
              </td>
              <td>
                <span class="chip <?= $schip ?>"><?= htmlspecialchars(ucfirst($p['status'])) ?></span>
              </td>
              <td>
                <a href="admin/ngo?id=<?= (int)$p['ngo_id'] ?>" class="name-strong"><?= htmlspecialchars($p['ngo_name']) ?></a><br>
                <small class="muted"><?= htmlspecialchars($p['ngo_city']) ?></small>
              </td>
              <td class="muted small"><?= htmlspecialchars($p['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($pets)): ?>
            <tr>
              <td colspan="9">
                <div class="empty-block">
                  <i class="bi bi-heart"></i>
                  <div class="fw-bold mt-1">No pets found</div>
                  <div class="text-muted small">Pets from verified NGOs will appear here.</div>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>