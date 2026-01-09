<?php $active='pets'; $errors=$errors??[]; $old=$old??[]; ?>

<div class="pet-add-wrap py-2">

  <style>
    .head-card{
      border:0; border-radius:1rem; padding:1rem 1.25rem;
      background:linear-gradient(135deg,#f7f3ff 0%, #eef2ff 100%);
      box-shadow:0 12px 28px rgba(0,0,0,.06);
    }
    .icon-pill{
      width:40px; height:40px; border-radius:12px; display:grid; place-items:center; color:#fff;
      background:linear-gradient(135deg,#6f42c1,#8f62ff); box-shadow:0 8px 20px rgba(111,66,193,.28); font-size:1.1rem;
    }

    .card-glow{ border:0; border-radius:1rem; box-shadow:0 12px 28px rgba(0,0,0,.06); }
    .label-req::after{ content:" *"; color:#dc3545; }

    .dz{
      position:relative;
      border:2px dashed rgba(111,66,193,.35);
      border-radius:.85rem; padding:1rem; background:#faf8ff;
      transition:.15s; cursor:pointer;
    }
    .dz:hover{ background:#f4efff; border-color:#8f62ff; }
    .dz .bi{ font-size:1.25rem; color:#6f42c1; }

    .form-text.text-danger{ display:block; margin-top:.35rem; }
    .helper{ color:#6c757d; font-size:.875rem; }

    .preview-card .preview-box{
      width:100%; height:220px; border-radius:.75rem; display:flex; align-items:center; justify-content:center;
      background:#f3f1ff; color:#6c757d; font-weight:600; font-size:.95rem;
      box-shadow:0 8px 18px rgba(0,0,0,.06);
    }
    .preview-card .preview-img{
      width:100%; height:220px; object-fit:cover; border-radius:.75rem; display:none;
    }
    .badge-soft{
      border:1px solid rgba(111,66,193,.25);
      background:#f8f3ff; color:#6f42c1; border-radius:999px; padding:.15rem .5rem; font-size:.75rem;
      white-space:nowrap;
    }
  </style>

  <!-- Page head -->
  <section class="mb-3">
    <div class="head-card d-flex flex-wrap justify-content-between align-items-center gap-2">
      <div class="d-flex align-items-center gap-3">
        <div class="icon-pill"><i class="bi bi-heart-fill"></i></div>
        <div>
          <h5 class="mb-0">Add a New Pet</h5>
          <div class="text-muted small">Provide clear details and a good photo to boost adoptions.</div>
        </div>
      </div>
      <a href="ngo/pets" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to My Pets</a>
    </div>
  </section>

  <div class="row g-3">
    <!-- FORM -->
    <div class="col-12 col-lg-7">
      <div class="card card-glow">
        <div class="card-header bg-white">
          <div class="d-flex align-items-center justify-content-between">
            <span class="fw-semibold"><i class="bi bi-paw me-1"></i> Pet Details</span>
            <span class="text-muted small">Fields marked with <span class="text-danger">*</span> are required</span>
          </div>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data" action="ngo/pet/add" novalidate id="petForm">
            <?= CSRF::input() ?>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label label-req">Name</label>
                <input type="text" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                <?php if(isset($errors['name'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['name']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label label-req">Species</label>
                <select name="species" class="form-select <?= isset($errors['species'])?'is-invalid':'' ?>">
                  <option value="">Select</option>
                  <?php foreach (['dog','cat'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($old['species']??'')===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['species'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['species']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label label-req">Sex</label>
                <select name="sex" class="form-select <?= isset($errors['sex'])?'is-invalid':'' ?>">
                  <option value="">Select</option>
                  <?php foreach (['male','female'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($old['sex']??'')===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['sex'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['sex']) ?></div><?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label label-req">Breed</label>
                <input type="text" name="breed" class="form-control <?= isset($errors['breed'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['breed'] ?? '') ?>">
                <?php if(isset($errors['breed'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['breed']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label label-req">Age (years)</label>
                <input type="number" name="age" class="form-control <?= isset($errors['age'])?'is-invalid':'' ?>" 
                       value="<?= htmlspecialchars($old['age'] ?? '') ?>" min="1" max="15">
                <?php if(isset($errors['age'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['age']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label label-req">Vaccinated</label>
                <select name="vaccinated" class="form-select <?= isset($errors['vaccinated'])?'is-invalid':'' ?>">
                  <option value="">Select</option>
                  <option value="yes" <?= ($old['vaccinated']??'')==='yes'?'selected':'' ?>>Yes</option>
                  <option value="no"  <?= ($old['vaccinated']??'')==='no'?'selected':''  ?>>No</option>
                </select>
                <?php if(isset($errors['vaccinated'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['vaccinated']) ?></div><?php endif; ?>
              </div>

              <div class="col-12">
                <label class="form-label">Description (optional)</label>
                <textarea name="description" rows="4" class="form-control <?= isset($errors['description'])?'is-invalid':'' ?>"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                <?php if(isset($errors['description'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['description']) ?></div><?php endif; ?>
              </div>

              <div class="col-12">
                <label class="form-label label-req">Image (JPG/PNG up to 2MB)</label>
                <label class="dz w-100">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                      <i class="bi bi-cloud-arrow-up"></i>
                      <span>Click to choose a photo or drop it here</span>
                    </div>
                    <span class="badge bg-light text-dark">Required</span>
                  </div>
                  <input required type="file" name="image" accept="image/*"
                         class="form-control <?= isset($errors['image'])?'is-invalid':'' ?>"
                         style="position:absolute; inset:0; opacity:0; cursor:pointer;">
                </label>
                <?php if(isset($errors['image'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['image']) ?></div><?php endif; ?>
              </div>
            </div>

            <div class="mt-4 d-flex gap-2">
              <a href="ngo/pets" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Cancel</a>
              <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- LIVE PREVIEW -->
    <div class="col-12 col-lg-5">
      <div class="card card-glow preview-card">
        <div class="card-header bg-white">
          <span class="fw-semibold"><i class="bi bi-eye me-1"></i> Live Preview</span>
        </div>
        <div class="card-body">
          <div class="preview-box" id="previewBox">No image selected</div>
          <img src="" class="preview-img mb-3" id="previewImg" alt="Preview">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <div class="fw-bold" id="pvName"><?= htmlspecialchars($old['name'] ?? 'Pet Name') ?></div>
              <div class="text-muted" id="pvMeta">
                <span id="pvSpecies"><?= htmlspecialchars(ucfirst($old['species'] ?? 'Species')) ?></span>
                <span> • </span>
                <span id="pvBreed"><?= htmlspecialchars($old['breed'] ?? 'Breed') ?></span>
              </div>
            </div>
            <span class="badge-soft" id="pvVacc"><?= (isset($old['vaccinated']) && $old['vaccinated']==='yes') ? 'Vaccinated' : 'Not vaccinated' ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    const name   = document.querySelector('input[name="name"]');
    const breed  = document.querySelector('input[name="breed"]');
    const species= document.querySelector('select[name="species"]');
    const vacc   = document.querySelector('select[name="vaccinated"]');
    const file   = document.querySelector('input[name="image"]');

    const pvName = document.getElementById('pvName');
    const pvBreed= document.getElementById('pvBreed');
    const pvSpec = document.getElementById('pvSpecies');
    const pvVacc = document.getElementById('pvVacc');
    const pvImg  = document.getElementById('previewImg');
    const pvBox  = document.getElementById('previewBox');

    if (name)    name.addEventListener('input',  e => pvName.textContent = e.target.value || 'Pet Name');
    if (breed)   breed.addEventListener('input', e => pvBreed.textContent = e.target.value || 'Breed');
    if (species) species.addEventListener('change', e => {
      const v = e.target.value; pvSpec.textContent = v ? (v[0].toUpperCase()+v.slice(1)) : 'Species';
    });
    if (vacc)    vacc.addEventListener('change', e => pvVacc.textContent = (e.target.value==='yes' ? 'Vaccinated' : 'Not vaccinated'));

    if (file){
      file.addEventListener('change', function(){
        const f = this.files && this.files[0];
        if (!f || !f.type.startsWith('image/')) {
          pvImg.style.display = "none";
          pvBox.style.display = "flex";
          return;
        }
        const reader = new FileReader();
        reader.onload = e => {
          pvImg.src = e.target.result;
          pvImg.style.display = "block";
          pvBox.style.display = "none";
        };
        reader.readAsDataURL(f);
      });
    }

    // enforce image required
    const form = document.getElementById('petForm');
    if (form){
      form.addEventListener('submit', function(e){
        if (!file.files.length){
          e.preventDefault();
          alert("Please select an image before submitting.");
        }
      });
    }
  })();
</script>
