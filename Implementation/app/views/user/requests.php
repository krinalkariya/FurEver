<div class="requests-wrap py-3">

  <style>
    .sheet{
      background:#fff;border-radius:1rem;padding:1rem 1rem;
      box-shadow:0 16px 40px rgba(111,66,193,.10);
    }
    @media (min-width:992px){ .sheet{padding:1.25rem 1.25rem;} }

    .page-title{
      display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;
    }
    .title-icon{
      width:40px;height:40px;border-radius:12px;display:grid;place-items:center;
      background:linear-gradient(135deg,#6f42c1,#8f62ff);color:#fff;
      box-shadow:0 10px 24px rgba(111,66,193,.28);
      font-size:1.1rem;
    }

    /* Modern table */
    .table-modern{
      --row-shadow: 0 6px 18px rgba(0,0,0,.05);
      border-collapse:separate;border-spacing:0 10px;
      width:100%;
    }
    .table-modern thead th{
      position:sticky;top:0;z-index:1;background:#f6f4ff;color:#5a5a7a;
      font-weight:700;border:none;padding:.75rem .9rem;
      text-transform:uppercase;font-size:.75rem;letter-spacing:.04em;
      border-top-left-radius:.75rem;border-top-right-radius:.75rem;
    }
    .table-modern tbody tr{
      background:#fff;box-shadow:var(--row-shadow);
    }
    .table-modern tbody td{
      border-top:none;border-bottom:none;vertical-align:middle;
      padding:.85rem .9rem;
    }
    .table-modern tbody tr:first-child td:first-child{border-top-left-radius:.75rem}
    .table-modern tbody tr:first-child td:last-child{border-top-right-radius:.75rem}
    .table-modern tbody tr td:first-child{border-left:1px solid rgba(111,66,193,.10)}
    .table-modern tbody tr td:last-child{border-right:1px solid rgba(111,66,193,.10)}
    .table-modern tbody tr:last-child td:first-child{border-bottom-left-radius:.75rem}
    .table-modern tbody tr:last-child td:last-child{border-bottom-right-radius:.75rem}
    .table-modern tbody tr:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(111,66,193,.12)}

    /* Status chips */
    .status-chip{display:inline-flex;align-items:center;gap:.4rem;border-radius:999px;padding:.35rem .7rem;font-weight:700;font-size:.8rem}
    .status-approved{color:#0f5132;background:#d1e7dd;border:1px solid #badbcc}
    .status-pending{color:#664d03;background:#fff3cd;border:1px solid #ffecb5}
    .status-rejected{color:#842029;background:#f8d7da;border:1px solid #f5c2c7}

    .pet-mini{width:56px;height:56px;object-fit:cover;border-radius:.6rem;box-shadow:0 6px 16px rgba(0,0,0,.12)}
    .meta-sub{color:#6c757d;font-size:.87rem}

    /* Empty state */
    .empty-state{text-align:center;border:0;border-radius:1rem;padding:2rem 1rem;background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);box-shadow:0 14px 34px rgba(111,66,193,.12)}
    .empty-state .empty-icon{font-size:2.2rem;color:#6f42c1;margin-bottom:.5rem}
    .empty-state h6{margin:0;font-weight:700}
    .empty-state p{margin:.25rem 0 1rem;color:#6c757d}
  </style>

  <div class="page-title">
    <div class="title-icon"><i class="bi bi-clipboard-check"></i></div>
    <div>
      <h3 class="mb-0">Your Adoption Requests</h3>
      <div class="meta-sub">Track every application and its status at a glance.</div>
    </div>
  </div>

  <?php if (empty($requests)): ?>
    <div class="empty-state">
      <div class="empty-icon"><i class="bi bi-paw-fill"></i></div>
      <h6>No requests yet</h6>
      <p>When you apply for a pet, you’ll see the status here.</p>
      <a class="btn btn-primary" href="user/pets">
        <i class="bi bi-search-heart me-1"></i> Browse pets
      </a>
    </div>
  <?php else: ?>
    <div class="sheet">
      <div class="table-responsive">
        <table class="table table-modern align-middle">
          <thead>
            <tr>
              <th>Pet</th>
              <th>Species/Breed</th>
              <th>Status</th>
              <th>Applied On</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($requests as $r): ?>
              <?php
                $status = strtolower($r['status']);
                $chipClass = $status==='approved' ? 'status-approved' : ($status==='pending' ? 'status-pending' : 'status-rejected');
                $chipIcon  = $status==='approved' ? 'bi-check-circle' : ($status==='pending' ? 'bi-hourglass-split' : 'bi-x-circle');
              ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <img src="<?= htmlspecialchars($r['image']) ?>" alt="" class="pet-mini me-2">
                    <div class="fw-semibold"><?= htmlspecialchars($r['pet_name']) ?></div>
                  </div>
                </td>
                <td class="meta-sub"><?= htmlspecialchars($r['species'] . ' • ' . $r['breed']) ?></td>
                <td>
                  <span class="status-chip <?= $chipClass ?>">
                    <i class="bi <?= $chipIcon ?>"></i> <?= ucfirst($status) ?>
                  </span>
                </td>
                <td class="meta-sub"><?= htmlspecialchars($r['created_at']) ?></td>
                <td class="text-end">
                  <a href="user/pet?id=<?= (int)$r['pet_id'] ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i> Details
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

</div>
