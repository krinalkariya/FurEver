<?php
$csrf   = CSRF::token();
$errors = $errors ?? [];
$role   = $role ?? 'user';
$email  = $email ?? '';
?>
<form method="post" action="<?= APP_URL ?>/login" novalidate data-validate="auth" id="login-form">
  <input type="hidden" name="_csrf" value="<?= $csrf ?>">

  <!-- EMAIL / USERNAME -->
  <div class="mb-3">
    <label class="form-label" id="id-label-email"><?= ($role==='admin') ? 'Username' : 'Email' ?></label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-envelope"></i></span>
      <input
        id="id-email"
        type="<?= ($role==='admin') ? 'text' : 'email' ?>"
        name="email"
        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
        value="<?= htmlspecialchars($email) ?>" required
        placeholder="<?= ($role==='admin') ? 'Enter admin username' : 'Enter email' ?>">
      <div class="invalid-feedback w-100 order-2 mt-1"><?= $errors['email'] ?? '' ?></div>
    </div>
  </div>

  <!-- PASSWORD + TOGGLE -->
  <div class="mb-3">
    <label class="form-label">Password</label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-lock"></i></span>
      <input
        id="id-password"
        type="password"
        name="password"
        class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required
        placeholder="Enter password">
      <div class="invalid-feedback w-100 order-3 mt-1"><?= $errors['password'] ?? '' ?></div>

      <!-- toggle eye -->
      <button class="btn btn-outline-secondary order-2" type="button" id="togglePassword" aria-label="Show password">
        <i class="bi bi-eye" id="toggleIcon"></i>
      </button>
    </div>
  </div>

  <!-- ROLE -->
  <div class="mb-3">
    <label class="form-label">Role</label>
    <select id="id-role" name="role" class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" required>
      <option value="user"  <?= $role==='user' ? 'selected' : '' ?>>User</option>
      <option value="ngo"   <?= $role==='ngo' ? 'selected' : '' ?>>NGO</option>
      <option value="admin" <?= $role==='admin' ? 'selected' : '' ?>>Admin</option>
    </select>
    <div class="invalid-feedback"><?= $errors['role'] ?? '' ?></div>
  </div>

  <input type="submit" class="btn btn-primary w-100" value="Login">
</form>

<hr class="my-4">
<div class="text-center">
  <p class="mb-2">New here?</p>
  <a href="<?= APP_URL ?>/register/user" class="btn btn-outline-primary btn-sm me-2">Create User Account</a>
  <a href="<?= APP_URL ?>/register/ngo" class="btn btn-outline-success btn-sm">Register as NGO</a>
</div>

<script>
(function(){
  var input = document.getElementById('id-password');
  var btn   = document.getElementById('togglePassword');
  var icon  = document.getElementById('toggleIcon');
  if(btn && input){
    btn.addEventListener('click', function(){
      var showing = input.getAttribute('type') === 'text';
      input.setAttribute('type', showing ? 'password' : 'text');
      if(icon){
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
      }
      btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
    });
  }
})();
</script>
