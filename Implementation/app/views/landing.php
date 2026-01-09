<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>FurEver · Online Pet Adoption System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --fur-primary: #6f42c1;
      --fur-secondary: #8f62ff;
      --fur-accent: #ffb703;
      --fur-ink: #29293a;
      --fur-bg: #f8f6ff;
    }

    body {
      background: #fff;
      color: var(--fur-ink);
      overflow-x: hidden;
      font-family: "Inter", sans-serif;
    }

    /* HERO */
    .hero {
      padding: 5rem 1rem 4rem;
      text-align: center;
      background: linear-gradient(135deg, var(--fur-primary) 0%, var(--fur-secondary) 100%);
      color: #fff;
      position: relative;
      overflow: hidden;
    }
    .hero h1 {
      font-weight: 800;
      font-size: 2.6rem;
      margin-bottom: .6rem;
    }
    .hero p {
      font-size: 1.1rem;
      opacity: 0.95;
      max-width: 640px;
      margin: 0 auto 1.8rem;
    }
    .hero .btn {
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      border-radius: 0.65rem;
    }
    .hero .btn-outline-light:hover {
      background: #fff;
      color: var(--fur-primary);
    }
    .hero svg {
      position: absolute;
      right: -10px; bottom: -10px;
      width: 250px;
      opacity: .18;
      transform: rotate(-8deg);
    }

    /* FEATURES */
    .features {
      padding: 4rem 0 3.5rem;
      background: linear-gradient(135deg, #ffffff 0%, #f8f6ff 100%);
    }
    .features h2 {
      text-align: center;
      font-weight: 700;
      color: var(--fur-primary);
      margin-bottom: 2rem;
    }
    .feature-card {
      border: 0;
      border-radius: 1rem;
      background: #fff;
      text-align: center;
      padding: 1.8rem 1rem;
      box-shadow: 0 10px 26px rgba(111,66,193,.08);
      transition: transform .18s ease, box-shadow .18s ease;
    }
    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 22px 44px rgba(111,66,193,.15);
    }
    .feature-card i {
      font-size: 2rem;
      color: var(--fur-primary);
      margin-bottom: 0.75rem;
    }
    .feature-card h5 {
      font-weight: 600;
      margin-bottom: 0.4rem;
    }
    .feature-card p {
      color: #6c757d;
      font-size: .95rem;
      margin: 0;
    }

    /* CTA */
    .cta {
      padding: 4rem 1rem;
      background: var(--fur-bg);
      text-align: center;
      border-top: 1px solid rgba(111,66,193,.1);
    }
    .cta h2 {
      font-weight: 700;
      color: var(--fur-primary);
      margin-bottom: 1rem;
    }
    .cta p {
      color: #555;
      max-width: 640px;
      margin: 0 auto 1.8rem;
      font-size: 1.05rem;
    }
    .cta .btn {
      background: var(--fur-primary);
      color: #fff;
      font-weight: 600;
      border-radius: 0.6rem;
      padding: 0.7rem 1.4rem;
    }
    .cta .btn:hover {
      background: var(--fur-secondary);
    }

    footer {
      background: #fff;
      text-align: center;
      padding: 1rem;
      font-size: .9rem;
      color: #555;
      border-top: 1px solid rgba(0,0,0,.06);
    }
  </style>
</head>
<body>
<?php require __DIR__ . '/layout/public_nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <div class="container">
    <h1>Find Your <span class="text-warning">Fur-Ever</span> Friend</h1>
    <p>Join India’s modern platform for adopting, listing, and reuniting pets — responsibly and transparently.</p>

    <div class="d-flex justify-content-center flex-wrap gap-3 mt-3">
      <a href="login" class="btn btn-light shadow-sm">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </a>
      <a href="register/user" class="btn btn-outline-light shadow-sm">
        <i class="bi bi-person-plus me-1"></i> Register as User
      </a>
      <a href="register/ngo" class="btn btn-outline-light shadow-sm">
        <i class="bi bi-building-heart me-1"></i> Register as NGO
      </a>
    </div>
  </div>
  <svg viewBox="0 0 200 200" fill="none">
    <g fill="#fff">
      <circle cx="45" cy="60" r="18"/>
      <circle cx="80" cy="40" r="16"/>
      <circle cx="115" cy="48" r="14"/>
      <circle cx="150" cy="70" r="16"/>
      <path d="M96 80c30-6 64 20 58 46-3 12-20 20-36 22-17 2-36-1-46-12s-8-30 2-40c6-6 12-12 22-16z"/>
    </g>
  </svg>
</section>

<!-- FEATURES -->
<section class="features">
  <div class="container">
    <h2>Why Choose FurEver?</h2>
    <div class="row g-4">
      <div class="col-6 col-md-3">
        <div class="feature-card">
          <i class="bi bi-patch-check-fill"></i>
          <h5>Verified NGOs</h5>
          <p>All organizations are verified by admin for trust and transparency.</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-card">
          <i class="bi bi-shield-lock-fill"></i>
          <h5>Secure OTP Login</h5>
          <p>Every account is verified via email OTP for safe authentication.</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-card">
          <i class="bi bi-heart-fill"></i>
          <h5>Simple Adoption</h5>
          <p>Browse, apply, and track — all from your personalized dashboard.</p>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-card">
          <i class="bi bi-search-heart"></i>
          <h5>Lost & Found</h5>
          <p>List missing or found pets to help reunite families quickly.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA / ABOUT US -->
<section class="cta">
  <div class="container">
    <h2>Learn More About Us</h2>
    <p>FurEver bridges the gap between pet seekers, NGOs, and admins — making adoption seamless and humane. Discover our mission and vision behind the platform.</p>
    <a href="about-public" class="btn">
      <i class="bi bi-info-circle me-1"></i> Visit About Page
    </a>
  </div>
</section>

<footer>
  © <?= date('Y') ?> FurEver · Online Pet Adoption System
</footer>

</body>
</html>