<?php $csrf = CSRF::token(); ?>
<form method="post" action="<?= APP_URL ?>/ngo/status" data-validate="auth" novalidate>
  <input type="hidden" name="_csrf" value="<?= $csrf ?>">

  <!-- EMAIL -->
  <div class="mb-3">
    <label class="form-label">NGO Email</label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-envelope"></i></span>
      <input type="email" name="email" class="form-control" required placeholder="Enter registered NGO email">
      <!-- immediate sibling for live validation -->
      <div class="invalid-feedback w-100 order-2 mt-1"></div>
    </div>
  </div>

  <button class="btn btn-primary w-100">Check Status</button>
</form>

<div class="text-center mt-3">
  <a href="<?= APP_URL ?>/login" class="small">Back to Login</a>
</div>
