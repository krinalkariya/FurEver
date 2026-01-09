<?php $active='ngos'; ?>
<div class="admin-ngos-list py-2">

  <style>
    /* Table styling */
    .table-ngos { border-collapse: separate; border-spacing: 0; width:100%; }
    .table-ngos thead th {
      font-weight:600; font-size:.85rem; text-transform:uppercase; letter-spacing:.03em;
      padding:.75rem .9rem; border-bottom:2px solid #e9ecef;
      color:#495057; white-space:nowrap;
    }
    .table-ngos tbody td {
      padding:.9rem; vertical-align:middle; border-top:1px solid #f0f0f0;
    }
    .table-ngos tbody tr:hover {
      background:#faf9ff;
    }

    /* Status chips */
    .chip{
      display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.32rem .65rem;
      font-weight:600; font-size:.78rem; border:1px solid transparent;
    }
    .chip-approved{ color:#0f5132; background:#d1e7dd; border-color:#badbcc; }
    .chip-rejected{ color:#842029; background:#f8d7da; border-color:#f5c2c7; }
    .chip-pending{ color:#664d03; background:#fff3cd; border-color:#ffecb5; }

    /* Reject form wrap */
    .rej-wrap{ background:#fcfbff; border:1px dashed #e3defa; border-radius:.65rem; padding:.5rem; }

    /* Links */
    .name-link{ font-weight:600; text-decoration:none; }
    .name-link:hover{ text-decoration:underline; }

    /* Empty state */
    .empty-block{
      border-radius:1rem; padding:1.25rem; text-align:center;
      background:#f8f9fa; border:1px dashed #ddd;
    }
    .empty-block .bi{ font-size:1.4rem; color:#6f42c1; }
  </style>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-ngos align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name / City</th>
            <th>Email / Phone</th>
            <th>Status</th>
            <th>Created</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ngos as $n): ?>
            <?php
              $s = strtolower($n['status']);
              $chip = $s==='approved' ? 'chip-approved' : ($s==='rejected' ? 'chip-rejected' : 'chip-pending');
              $rowId = (int)$n['ngo_id'];
            ?>
            <tr>
              <td class="fw-bold">#<?= $rowId ?></td>
              <td>
                <div><a class="name-link" href="admin/ngo?id=<?= $rowId ?>"><?= htmlspecialchars($n['name']) ?></a></div>
                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($n['city']) ?></small>
              </td>
              <td>
                <div><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($n['email']) ?></div>
                <small class="text-muted"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($n['phone']) ?></small>
              </td>
              <td><span class="chip <?= $chip ?> text-uppercase"><?= htmlspecialchars($n['status']) ?></span></td>
              <td class="text-muted small"><?= htmlspecialchars($n['created_at']) ?></td>
              <td class="text-end">
                <?php if ($n['status'] === 'pending'): ?>
                  <form method="post" action="admin/ngos/approve?id=<?= $rowId ?>" class="d-inline">
                    <?= CSRF::input() ?>
                    <button class="btn btn-sm btn-success"><i class="bi bi-check2 me-1"></i>Approve</button>
                  </form>

                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#rej-<?= $rowId ?>">
                    <i class="bi bi-x-circle me-1"></i>Reject
                  </button>

                  <div class="collapse mt-2" id="rej-<?= $rowId ?>">
                    <div class="rej-wrap">
                      <form method="post" action="admin/ngos/reject?id=<?= $rowId ?>">
                        <?= CSRF::input() ?>
                        <div class="input-group input-group-sm">
                          <input type="text" name="status_reason" class="form-control" placeholder="Reason (optional)" maxlength="200">
                          <button class="btn btn-danger"><i class="bi bi-send"></i></button>
                        </div>
                      </form>
                    </div>
                  </div>
                <?php else: ?>
                  <a class="btn btn-sm btn-outline-secondary" href="admin/ngo?id=<?= $rowId ?>">
                    <i class="bi bi-box-arrow-up-right me-1"></i>View
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($ngos)): ?>
            <tr>
              <td colspan="7">
                <div class="empty-block">
                  <i class="bi bi-building"></i>
                  <div class="fw-bold mt-1">No NGOs yet</div>
                  <div class="text-muted small">New registrations will appear here for review and approval.</div>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>