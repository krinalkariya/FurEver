<?php $active='lostfound'; $errors=$errors??[]; $old=$old??[]; ?>

<div class="lostfound-wrap py-2">
  <style>
    .lf-card{border:0;border-radius:1rem;box-shadow:0 12px 28px rgba(0,0,0,.06);}
    .label-req::after{content:" *";color:#dc3545;}
    .form-text.text-danger{display:block;margin-top:.35rem;}
    .lf-badge{padding:.2rem .6rem;border-radius:.5rem;font-size:.8rem;font-weight:600;}
    .lf-badge.lost{background:#ffe5e5;color:#dc3545;}
    .lf-badge.found{background:#e6ffe9;color:#198754;}
  </style>

  <!-- Form -->
  <section class="mb-3">
    <div class="card lf-card">
      <div class="card-header bg-white">
        <span class="fw-semibold"><i class="bi bi-exclamation-triangle"></i> Report Lost / Found Pet</span>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data" action="user/lostfound" id="lfForm" novalidate>
          <?= CSRF::input() ?>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label label-req">Type</label>
              <select name="type" class="form-select <?= isset($errors['type'])?'is-invalid':'' ?>" required>
                <option value="">Select</option>
                <option value="lost" <?= ($old['type']??'')==='lost'?'selected':'' ?>>Lost</option>
                <option value="found" <?= ($old['type']??'')==='found'?'selected':'' ?>>Found</option>
              </select>
              <?php if(isset($errors['type'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['type']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-8">
              <label class="form-label label-req">Image (JPG/PNG up to 2MB)</label>
              <input type="file" name="image" accept="image/*"
                     class="form-control <?= isset($errors['image'])?'is-invalid':'' ?>" required>
              <?php if(isset($errors['image'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['image']) ?></div><?php endif; ?>
            </div>
          </div>
          <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-upload me-1"></i> Submit</button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Listing -->
  <section>
    <div class="row g-3">
      <?php foreach($reports as $r): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card lf-card h-100">
            <img src="<?= htmlspecialchars($r['image']) ?>" class="card-img-top" alt="LostFound">
            <div class="card-body">
              <span class="lf-badge <?= $r['type']==='lost'?'lost':'found' ?>">
                <?= ucfirst($r['type']) ?>
              </span>
              <div class="mt-2 fw-bold"><?= htmlspecialchars($r['name']) ?> (<?= htmlspecialchars($r['city']) ?>)</div>
              <div class="small text-muted">📞 <?= htmlspecialchars($r['phone']) ?><br>✉ <?= htmlspecialchars($r['email']) ?></div>
              <div class="small text-muted mt-1"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if(empty($reports)): ?>
        <div class="empty-state">
          <div class="empty-icon"><i class="bi bi-paw-fill"></i></div>
          <h6>No reports yet</h6>
          <p>Be the first to add a Lost or Found pet.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>
</div>

<script>
(function(){
  const form = document.getElementById('lfForm');
  if (!form) return;
  form.addEventListener('submit', function(e){
    let ok = true;
    const type = form.querySelector('select[name="type"]');
    const img  = form.querySelector('input[name="image"]');

    if (!type.value){
      ok = false;
      alert("Please select Lost or Found");
    }
    if (!img.files.length){
      ok = false;
      alert("Please upload an image");
    }
    if (!ok) e.preventDefault();
  });
})();
</script>