<?php
$title='Register NGO';
$csrf = CSRF::token();
$errors = $errors ?? [];
$name  = $name  ?? '';
$city  = $city  ?? '';
$email = $email ?? '';
$phone = $phone ?? '';
?>
<form method="post" action="<?= APP_URL ?>/register/ngo" data-validate="auth" novalidate>
  <input type="hidden" name="_csrf" value="<?= $csrf ?>">

  <!-- NGO NAME -->
  <div class="mb-3">
    <label class="form-label">NGO Name</label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-building"></i></span>
      <input name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" required minlength="2" maxlength="100" placeholder="Enter NGO name" value="<?= htmlspecialchars($name) ?>">
      <div class="invalid-feedback w-100 order-2 mt-1"><?= $errors['name'] ?? '' ?></div>
    </div>
  </div>

  <!-- CITY -->
  <div class="mb-3">
    <label class="form-label">City</label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
      <input name="city" class="form-control <?= isset($errors['city'])?'is-invalid':'' ?>" required minlength="2" maxlength="50" placeholder="City" value="<?= htmlspecialchars($city) ?>">
      <div class="invalid-feedback w-100 order-2 mt-1"><?= $errors['city'] ?? '' ?></div>
    </div>
  </div>

  <!-- OFFICIAL EMAIL -->
  <div class="mb-3">
    <label class="form-label">Official Email</label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-envelope"></i></span>
      <input type="email" name="email" class="form-control <?= isset($errors['email'])?'is-invalid':'' ?>" required placeholder="ngo@example.org" value="<?= htmlspecialchars($email) ?>">
      <div class="invalid-feedback w-100 order-2 mt-1"><?= $errors['email'] ?? '' ?></div>
    </div>
  </div>

  <!-- PHONE -->
  <div class="mb-3">
    <label class="form-label">Phone</label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-telephone"></i></span>
      <input type="tel" name="phone"
             inputmode="numeric" autocomplete="tel"
             pattern="\d{10}" maxlength="10"
             class="form-control <?= isset($errors['phone'])?'is-invalid':'' ?>" required placeholder="10-digit number"
             value="<?= htmlspecialchars($phone) ?>">
      <div class="invalid-feedback w-100 order-2 mt-1"><?= $errors['phone'] ?? '' ?></div>
    </div>
  </div>

  <!-- PASSWORD + TOGGLE -->
  <div class="mb-3">
    <label class="form-label">Password</label>
    <div class="input-group flex-wrap">
      <span class="input-group-text"><i class="bi bi-lock"></i></span>
      <input id="id-password" type="password" name="password" class="form-control <?= isset($errors['password'])?'is-invalid':'' ?>" required minlength="8" maxlength="64" placeholder="Create a strong password">
      <div class="invalid-feedback w-100 order-3 mt-1"><?= $errors['password'] ?? '' ?></div>
      <button class="btn btn-outline-secondary order-2" type="button" id="togglePassword" aria-label="Show password">
        <i class="bi bi-eye" id="toggleIcon"></i>
      </button>
    </div>
  </div>

  <button class="btn btn-primary w-100">Register & Verify</button>
  <div class="text-muted small mt-2">After verification, admin approval is required before login.</div>

  <div class="text-center mt-3">
    <div class="mb-2">
      <span class="small">Already have an account?</span>
      <a href="<?= APP_URL ?>/login" class="small">Login here</a>
    </div>
    <div>
      <span class="small">Already applied?</span>
      <a href="<?= APP_URL ?>/ngo/status" class="small">Check approval status</a>
    </div>
  </div>
</form>

<script>
(function(){
  var input = document.getElementById('id-password');
  var btn   = document.getElementById('togglePassword');
  var icon  = document.getElementById('toggleIcon');
  if(btn && input){
    btn.addEventListener('click', function(){
      var showing = input.getAttribute('type') === 'text';
      input.setAttribute('type', showing ? 'password' : 'text');
      if(icon){ icon.classList.toggle('bi-eye'); icon.classList.toggle('bi-eye-slash'); }
      btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
    });
  }
})();
</script>
