<?php
$csrf  = CSRF::token();
$email = $_GET['email'] ?? '';
$role  = $_GET['role']  ?? 'user';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="mb-2">Verify your email</h5>
        <p class="text-muted small">We sent a 6‑digit OTP to <strong><?= htmlspecialchars($email) ?></strong>.</p>

        <!-- VERIFY FORM (live validation + digits-only) -->
        <form method="post" action="<?= APP_URL ?>/verify" data-validate="auth" novalidate>
          <input type="hidden" name="_csrf" value="<?= $csrf ?>">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
          <input type="hidden" name="role"  value="<?= htmlspecialchars($role) ?>">

          <div class="mb-3">
            <label class="form-label">Enter 6-digit code</label>
            <div class="input-group flex-wrap">
              <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
              <input
                type="text"
                name="code"
                class="form-control"
                inputmode="numeric"
                autocomplete="one-time-code"
                pattern="\d{6}"
                maxlength="6"
                required
                placeholder="••••••">
              <!-- immediate sibling for real-time validation.js -->
              <div class="invalid-feedback w-100 order-2 mt-1"></div>
            </div>
          </div>

          <button class="btn btn-primary w-100">Verify</button>
        </form>

        <!-- RESEND -->
        <form class="mt-3" method="post" action="<?= APP_URL ?>/resend-otp">
          <input type="hidden" name="_csrf" value="<?= $csrf ?>">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
          <input type="hidden" name="role"  value="<?= htmlspecialchars($role) ?>">
          <button class="btn btn-outline-secondary w-100">Resend code</button>
        </form>
      </div>
    </div>
  </div>
</div>
