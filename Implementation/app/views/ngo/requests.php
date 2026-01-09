<?php $active='requests'; ?>
<div class="ngo-requests-wrap py-2">

  <style>
    /* Scoped to requests page */
    .head-card{
      border:0; border-radius:1rem; padding:1rem 1.25rem;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);
      box-shadow:0 12px 28px rgba(0,0,0,.06);
    }
    .icon-pill{
      width:40px; height:40px; border-radius:12px; display:grid; place-items:center; color:#fff;
      background:linear-gradient(135deg,#6f42c1,#8f62ff); box-shadow:0 8px 20px rgba(111,66,193,.28); font-size:1.1rem;
    }

    .filter-card{ border:0; border-radius:1rem; box-shadow:0 12px 28px rgba(0,0,0,.06); }
    .chip{display:inline-flex;align-items:center;gap:.45rem;border:1.5px solid rgba(111,66,193,.3);background:#fff;border-radius:999px;padding:.45rem .85rem;font-weight:600;font-size:.92rem;text-decoration:none;transition:.15s;box-shadow:0 8px 18px rgba(111,66,193,.08)}
    .chip:hover{background:#f4efff;border-color:#8f62ff;box-shadow:0 14px 28px rgba(111,66,193,.18)}
    .chip.active{background:#6f42c1;color:#fff;border-color:#6f42c1;box-shadow:0 10px 26px rgba(111,66,193,.35)}
    .chip .bi{opacity:.9}

    .table-modern{ border-collapse:separate; border-spacing:0 10px; width:100%; }
    .table-modern thead th{
      position:sticky; top:0; z-index:1; background:#f6f4ff; color:#5a5a7a;
      font-weight:700; border:none; padding:.75rem .9rem; text-transform:uppercase; font-size:.75rem; letter-spacing:.04em;
      border-top-left-radius:.75rem; border-top-right-radius:.75rem;
    }
    .table-modern tbody tr{ background:#fff; box-shadow:0 6px 18px rgba(0,0,0,.05); }
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
    .empty-block .bi{ font-size:1.4rem; color:#6f42c1; }
  </style>

  <!-- Header -->
  <div class="head-card d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-3">
      <div class="icon-pill"><i class="bi bi-inbox"></i></div>
      <div>
        <h5 class="mb-0">Adoption Requests</h5>
        <div class="text-muted small">Review, track, and manage applications from adopters.</div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="card filter-card mb-3">
    <div class="card-body">
      <form class="row g-2 align-items-center" method="GET" action="ngo/requests">
        <!-- Quick chips (mirror of dropdown) -->
        <div class="col-12 mt-1">
          <?php
            $curr = $status ?? '';
            $base = 'ngo/requests';
          ?>
          <a class="chip <?= $curr===''?'active':'' ?>" href="<?= $base ?>"><i class="bi bi-grid"></i> All</a>
          <a class="chip <?= $curr==='pending'?'active':'' ?>" href="<?= $base ?>?status=pending"><i class="bi bi-hourglass-split"></i> Pending</a>
          <a class="chip <?= $curr==='approved'?'active':'' ?>" href="<?= $base ?>?status=approved"><i class="bi bi-check-circle"></i> Approved</a>
          <a class="chip <?= $curr==='rejected'?'active':'' ?>" href="<?= $base ?>?status=rejected"><i class="bi bi-x-circle"></i> Rejected</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Table -->
  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Pet</th>
              <th>Applicant</th>
              <th>City</th>
              <th>Status</th>
              <th>Created</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($requests as $r): ?>
              <?php
                $rs = strtolower($r['status']);
                $chip = $rs==='pending' ? 'chip-pending' : ($rs==='approved' ? 'chip-approved' : 'chip-rejected');
                $icon = $rs==='pending' ? 'bi-hourglass-split' : ($rs==='approved' ? 'bi-check-circle' : 'bi-x-circle');
              ?>
              <tr>
                <td>#<?= (int)$r['request_id'] ?></td>
                <td class="fw-semibold"><?= htmlspecialchars($r['pet_name']) ?></td>
                <td><?= htmlspecialchars($r['user_name']) ?></td>
                <td class="text-muted"><?= htmlspecialchars($r['user_city']) ?></td>
                <td><span class="status-chip <?= $chip ?>"><i class="bi <?= $icon ?>"></i> <?= ucfirst($r['status']) ?></span></td>
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
                <td colspan="7">
                  <div class="empty-block">
                    <i class="bi bi-inbox"></i>
                    <div class="fw-bold mt-1">No requests found</div>
                    <div class="text-muted small">Try changing the status filter or check back later.</div>
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