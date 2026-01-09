<?php $active='users'; ?>
<div class="admin-users-list py-2">

  <style>
    /* Scoped, clean table */
    .table-users { border-collapse: separate; border-spacing: 0; width:100%; }
    .table-users thead th{
      font-weight:600; font-size:.85rem; text-transform:uppercase; letter-spacing:.03em;
      padding:.75rem .9rem; border-bottom:2px solid #e9ecef; color:#495057; white-space:nowrap;
    }
    .table-users tbody td{
      padding:.9rem; vertical-align:middle; border-top:1px solid #f0f0f0;
    }
    .table-users tbody tr:hover{ background:#faf9ff; }

    /* Chips (consistent across admin pages) */
    .chip{
      display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.32rem .65rem;
      font-weight:600; font-size:.78rem; border:1px solid transparent;
    }
    .chip-yes{ color:#0f5132; background:#d1e7dd; border-color:#badbcc; }
    .chip-no{ color:#41464b; background:#e2e3e5; border-color:#d3d6d8; }
    .chip-active{ color:#0f5132; background:#d1e7dd; border-color:#badbcc; }
    .chip-inactive{ color:#664d03; background:#fff3cd; border-color:#ffecb5; }

    /* Names */
    .name-strong{ font-weight:600; }
    .muted{ color:#6c757d; }

    /* Actions */
    .row-actions .btn{ border-radius:.55rem; }
    .toggle-btn{ min-width:92px; } /* keeps column width steady */
    .empty-block{
      border-radius:1rem; padding:1.25rem; text-align:center;
      background:#f8f9fa; border:1px dashed #ddd;
    }
    .empty-block .bi{ font-size:1.4rem; color:#6f42c1; }
  </style>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-users align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name / City</th>
            <th>Email / Phone</th>
            <th>Verified</th>
            <th>Status</th>
            <th>Joined</th>
            <th class="text-end">Toggle</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <?php
              $uid      = (int)$u['user_id'];
              $verified = ((int)$u['email_verified']===1);
              $isActive = ($u['status']==='active');
            ?>
            <tr>
              <td class="fw-bold">#<?= $uid ?></td>
              <td>
                <div class="name-strong"><?= htmlspecialchars($u['name']) ?></div>
                <small class="muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($u['city']) ?></small>
              </td>
              <td>
                <div><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($u['email']) ?></div>
                <small class="muted"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($u['phone']) ?></small>
              </td>
              <td>
                <span class="chip <?= $verified ? 'chip-yes' : 'chip-no' ?>">
                  <?= $verified ? 'Yes' : 'No' ?>
                </span>
              </td>
              <td>
                <span class="chip <?= $isActive ? 'chip-active' : 'chip-inactive' ?>">
                  <?= htmlspecialchars(ucfirst($u['status'])) ?>
                </span>
              </td>
              <td class="muted small"><?= htmlspecialchars($u['created_at']) ?></td>
              <td class="text-end row-actions">
                <form method="post" action="admin/users/toggle?id=<?= $uid ?>" class="d-inline">
                  <?= CSRF::input() ?>
                  <button class="btn btn-sm btn-outline-primary toggle-btn">
                    <?= $isActive ? 'Deactivate' : 'Activate' ?>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($users)): ?>
            <tr>
              <td colspan="7">
                <div class="empty-block">
                  <i class="bi bi-people"></i>
                  <div class="fw-bold mt-1">No users found</div>
                  <div class="text-muted small">Newly registered users will appear here.</div>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>