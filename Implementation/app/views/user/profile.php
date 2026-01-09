<div class="profile-wrap py-3" style="max-width:760px;margin-inline:auto;">

  <style>
    .sheet{
      background:#fff;border-radius:1rem;padding:1.25rem 1.25rem;
      box-shadow:0 16px 40px rgba(111,66,193,.10);
    }
    @media (min-width:992px){ .sheet{padding:1.5rem 1.75rem;} }

    .page-title{display:flex;align-items:center;gap:.9rem;margin-bottom:1rem}
    .avatar{
      width:48px;height:48px;border-radius:14px;display:grid;place-items:center;
      background:linear-gradient(135deg,#6f42c1,#8f62ff);color:#fff;font-weight:800;
      box-shadow:0 10px 24px rgba(111,66,193,.28);
      letter-spacing:.5px
    }
    .subtitle{color:#6c757d}

    .form-label{font-weight:600}
    .form-control{border-radius:.75rem}
    .btn{border-radius:.65rem}
  </style>

  <?php $initial = strtoupper($user['name'][0] ?? 'U'); ?>

  <div class="page-title">
    <div class="avatar"><?= htmlspecialchars($initial) ?></div>
    <div>
      <h3 class="mb-0">Edit Profile</h3>
      <div class="subtitle">Keep your contact details up to date.</div>
    </div>
  </div>

  <div class="sheet">
    <form method="post" action="user/profile" novalidate>
      <?= CSRF::input(); ?>

      <!-- NAME -->
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-person me-1 text-primary"></i> Name</label>
        <input type="text" name="name"
               class="form-control <?= isset($errors['name'])?'is-invalid':''; ?>"
               value="<?= htmlspecialchars($user['name']); ?>">
        <?php if (!empty($errors['name'])): ?>
          <div class="invalid-feedback"><?= htmlspecialchars($errors['name']); ?></div>
        <?php endif; ?>
      </div>

      <!-- EMAIL (read-only) -->
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-envelope me-1 text-primary"></i> Email (read-only)</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" disabled>
        <div class="form-text">Email can’t be changed.</div>
      </div>

      <!-- PHONE -->
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-telephone me-1 text-primary"></i> Phone</label>
        <input type="text" name="phone"
               class="form-control <?= isset($errors['phone'])?'is-invalid':''; ?>"
               value="<?= htmlspecialchars($user['phone']); ?>">
        <?php if (!empty($errors['phone'])): ?>
          <div class="invalid-feedback"><?= htmlspecialchars($errors['phone']); ?></div>
        <?php endif; ?>
      </div>

      <!-- CITY -->
      <div class="mb-3">
        <label class="form-label"><i class="bi bi-geo-alt me-1 text-primary"></i> City</label>
        <input type="text" name="city"
               class="form-control <?= isset($errors['city'])?'is-invalid':''; ?>"
               value="<?= htmlspecialchars($user['city']); ?>">
        <?php if (!empty($errors['city'])): ?>
          <div class="invalid-feedback"><?= htmlspecialchars($errors['city']); ?></div>
        <?php endif; ?>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary">
          <i class="bi bi-save me-1"></i> Save Changes
        </button>
        <a class="btn btn-outline-secondary" href="user/dashboard">
          <i class="bi bi-arrow-left me-1"></i> Cancel
        </a>
      </div>
    </form>
  </div>
</div>
