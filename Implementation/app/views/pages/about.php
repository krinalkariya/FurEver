<div class="about-wrap py-4">

  <style>
    .hero-about{
      position:relative;border-radius:1rem;overflow:hidden;
      background:#111;color:#fff;min-height:320px;
      box-shadow:0 18px 44px rgba(111,66,193,.18);
    }
    .hero-about img{
      position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.5;
      transform:scale(1.02);
    }
    .hero-mask{
      position:absolute;inset:0;
      background:linear-gradient(135deg, rgba(15,15,25,.7), rgba(90,60,160,.55));
    }
    .hero-content{position:relative;z-index:2;padding:2rem}
    .hero-badges .badge{
      background:rgba(255,255,255,.15);backdrop-filter:blur(6px);
      border:1px solid rgba(255,255,255,.25);font-weight:600
    }

    .section-card{
      background:#fff;border-radius:1rem;padding:1.25rem;
      box-shadow:0 16px 40px rgba(111,66,193,.10)
    }
    @media (min-width:992px){ .hero-content{padding:3rem}; .section-card{padding:1.75rem} }

    .value-card{
      border:0;border-radius:1rem;background:#fff;height:100%;
      box-shadow:0 12px 28px rgba(0,0,0,.06);transition:.18s
    }
    .value-card:hover{transform:translateY(-3px);box-shadow:0 24px 48px rgba(111,66,193,.16)}
    .value-icon{
      width:52px;height:52px;border-radius:14px;display:grid;place-items:center;
      background:linear-gradient(135deg,#6f42c1,#8f62ff);color:#fff;font-size:1.2rem;
      box-shadow:0 10px 24px rgba(111,66,193,.28)
    }
  </style>

  <!-- HERO -->
  <section class="hero-about mb-4">
    <img src="assets/images/hero.jpg" alt="About">
    <div class="hero-mask"></div>
    <div class="hero-content">
      <div class="hero-badges d-flex gap-2 mb-2 flex-wrap">
        <span class="badge"><i class="bi bi-patch-check-fill me-1"></i> Verified NGOs</span>
        <span class="badge"><i class="bi bi-shield-heart me-1"></i> Responsible Adoption</span>
        <span class="badge"><i class="bi bi-hand-thumbs-up me-1"></i> Community-first</span>
      </div>
      <h1 class="fw-bold display-6 mb-2">About FurEver</h1>
      <p class="lead mb-0" style="max-width:860px">
        We’re on a mission to connect people with pets in need—safely, transparently, and with heart.
        FurEver bridges seekers and verified NGOs so every adoption is a great match.
      </p>
    </div>
  </section>

  <!-- OUR MISSION (kept) -->
  <section class="mb-4 section-card">
    <div class="row g-3 align-items-center">
      <div class="col-lg-6">
        <img src="assets/images/mission.jpeg" alt="Mission" class="img-fluid rounded" style="box-shadow:0 14px 34px rgba(0,0,0,.12)">
      </div>
      <div class="col-lg-6">
        <h3 class="mb-2">Our Mission</h3>
        <p class="text-muted mb-3">
          Digitize the adoption journey and promote responsible pet ownership.
          NGOs list pets; adopters browse, apply, and track—admins ensure a safe and verified ecosystem.
        </p>
        <div class="row g-3">
          <div class="col-sm-6">
            <div class="value-card p-3">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="value-icon"><i class="bi bi-heart"></i></div>
                <div class="fw-bold">Welfare First</div>
              </div>
              <div class="text-muted">Every decision prioritizes animal well-being and long-term care.</div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="value-card p-3">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="value-icon"><i class="bi bi-shield-check"></i></div>
                <div class="fw-bold">Trusted Network</div>
              </div>
              <div class="text-muted">Only verified NGOs; transparent statuses and clear communication.</div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="value-card p-3">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="value-icon"><i class="bi bi-people"></i></div>
                <div class="fw-bold">Community</div>
              </div>
              <div class="text-muted">We bring adopters, NGOs, and admins together in one place.</div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="value-card p-3">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="value-icon"><i class="bi bi-gear"></i></div>
                <div class="fw-bold">Simple by Design</div>
              </div>
              <div class="text-muted">Clean UI today, scalable for tomorrow—notifications, reviews, and more.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
