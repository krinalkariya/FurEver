<?php /* Minimal layout for auth with sidebar (flash handled in layout) */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'FurEver') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

  <style>
    :root{ --bg:#f3f6fb; --card:#ffffff; --border:#e5e7eb; }
    body{background:linear-gradient(180deg,#f7f9ff 0%, var(--bg) 100%);}
    .auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
    .auth-card{width:100%;max-width:980px;border:1px solid var(--border);border-radius:1rem;background:var(--card);overflow:hidden;}
    .invalid-feedback{display:block}

    /* Brand (right column) */
    .auth-brand{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:.75rem;color:#0d6efd;font-size:2rem;}
    .auth-brand i{font-size:2rem;}

    /* Sidebar */
    .auth-side{background: linear-gradient(135deg,#4f80ff 0%, #9256ff 100%); color:#fff; position:relative;}
    .auth-side-inner{padding:2rem; min-height:100%; display:flex; flex-direction:column; justify-content:center;}
    .auth-side .bi, .auth-side .fa{opacity:.95;}
    .auth-side .badge-soft{background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.25); color:#fff; border-radius:999px; padding:.35rem .7rem; font-weight:600;}
    .auth-side-wave{position:absolute; left:0; right:0; bottom:0; height:84px; opacity:.25; pointer-events:none;}
    .auth-side-wave svg{width:100%; height:100%; display:block;}
    .auth-side-wave path{fill:#ffffff;}

    /* Right column paddings */
    .auth-right{padding:1.25rem;}
    @media (min-width: 992px){
      .auth-right{padding:2rem 2.25rem;}
    }

    /* Constrain right content so alerts/forms don't go full width */
    .auth-right-inner{max-width:560px;margin:0 auto;}

    /* Compact alerts so they don't push the form down too much */
    .alert-compact{padding:.5rem .75rem; font-size:.9rem; border-radius:.5rem;}

    /* For input-group + live feedback spacing (used in views) */
    .input-group.flex-wrap .invalid-feedback{ width:100%; margin-top:.25rem; }

    /* Password helpers if needed by views */
    .password-field{ position:relative; }
    .password-field .toggle-btn{
      position:absolute; right:.5rem; top:50%; transform:translateY(-50%);
      z-index:2;
    }
    .password-field input{ padding-right:2.5rem; }

    .field-icon-wrap{ position:relative; }
    .field-icon{ position:absolute; left:.75rem; top:50%; transform:translateY(-50%); pointer-events:none; opacity:.7; }
    .field-icon-wrap > input.form-control{ padding-left:2.25rem; }
    .password-field > input.form-control{ padding-right:2.75rem; }
    .password-field .toggle-btn{ position:absolute; right:.5rem; top:50%; transform:translateY(-50%); z-index:2; }
  </style>
</head>
<body>
  
  <div class="auth-wrap">
    <div class="card auth-card shadow-lg border-0">
      <div class="card-body p-0">
        <div class="row g-0">
          <!-- Sidebar (hidden on small screens) -->
          <div class="col-lg-5 d-none d-lg-block auth-side">
            <div class="auth-side-inner">
              <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-paw fs-3"></i>
                <div class="fw-bold">FurEver</div>
              </div>

              <h4 class="fw-semibold mb-2">Find your new best friend</h4>
              <p class="mb-4 text-white-50">Verified NGOs • Safe adoptions • Transparent process</p>

              <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="badge-soft"><i class="bi bi-shield-check me-1"></i>Secure OTP</span>
                <span class="badge-soft"><i class="bi bi-check2-circle me-1"></i>NGO Approval</span>
                <span class="badge-soft"><i class="bi bi-heart-fill me-1"></i>Easy Tracking</span>
              </div>

              <ul class="list-unstyled small mb-4">
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="bi bi-lock-fill"></i>
                  <span>Accounts protected with email OTP & CSRF</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="bi bi-people-fill"></i>
                  <span>Only approved NGOs can list pets</span>
                </li>
                <li class="d-flex align-items-start gap-2">
                  <i class="bi bi-envelope-open-heart-fill"></i>
                  <span>Track requests until approved or rejected</span>
                </li>
              </ul>

              <div class="fst-italic small">“Adopt, don’t shop — give a loving home today.”</div>
            </div>

            <div class="auth-side-wave" aria-hidden="true">
              <svg viewBox="0 0 600 120" preserveAspectRatio="none"><path d="M0,40 C150,140 450,-60 600,40 L600,120 L0,120 Z"></path></svg>
            </div>
          </div>

          <!-- Right column -->
          <div class="col-12 col-lg-7">
            <div class="auth-right">
              <div class="auth-right-inner">

               

                <?php
                  // Read flashes ONCE here so they appear immediately after redirects (e.g., verify -> login).
                  $flashSuccess = Session::flash('success');
                  $flashError   = Session::flash('error');
                ?>
                <?php if (!empty($flashSuccess)): ?>
                  <div class="alert alert-success alert-compact mb-3"><?= htmlspecialchars($flashSuccess) ?></div>
                <?php endif; ?>
                <?php if (!empty($flashError)): ?>
                  <div class="alert alert-danger alert-compact mb-3"><?= htmlspecialchars($flashError) ?></div>
                <?php endif; ?>
                   <div class="auth-brand">
                  <i class="fa-solid fa-paw"></i>
                  <strong>FurEver</strong>
                </div>
                <h3 class="text-center mb-4"><?= htmlspecialchars($title ?? '') ?></h3>

                <?php require $viewPath; ?>

              </div>
            </div>
          </div>
          <!-- /Right column -->

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= APP_URL ?>/assets/js/validation.js"></script>
</body>
</html>
