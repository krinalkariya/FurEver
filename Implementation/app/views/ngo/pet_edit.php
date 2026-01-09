<?php $active='pets'; $errors=$errors??[]; $old=$old??[]; ?>

<div class="pet-edit-wrap py-2">

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
    .label-req::after{ content:""; } /* edit: none required marker */

    /* Dropzone feel (input remains native for validation) */
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

    /* Live preview */
    .preview-card .preview-box{
      width:100%; height:220px; border-radius:.75rem; display:flex; align-items:center; justify-content:center;
      background:#f3f1ff; color:#6c757d; font-weight:600; font-size:.95rem;
      box-shadow:0 8px 18px rgba(0,0,0,.06);
    }
    .preview-card .preview-img{
      width:100%; height:220px; object-fit:cover; border-radius:.75rem; display:none;
      box-shadow:0 8px 18px rgba(0,0,0,.06);
    }
    .badge-soft{
      border:1px solid rgba(111,66,193,.25);
      background:#f8f3ff; color:#6f42c1; border-radius:999px; padding:.15rem .5rem; font-size:.75rem;
      white-space:nowrap;
    }
  </style>

  <!-- Header -->
  <section class="mb-3">
    <div class="head-card d-flex flex-wrap justify-content-between align-items-center gap-2">
      <div class="d-flex align-items-center gap-3">
        <div class="icon-pill"><i class="bi bi-heart-fill"></i></div>
        <div>
          <h5 class="mb-0">Edit Pet #<?= (int)$pet['pet_id'] ?></h5>
          <div class="text-muted small">Update details and photos to keep your listing fresh.</div>
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
          <span class="fw-semibold"><i class="bi bi-heart-fill me-1"></i> Pet Details</span>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data" action="ngo/pet/edit?id=<?= (int)$pet['pet_id'] ?>" novalidate id="petEditForm">
            <?= CSRF::input() ?>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                <?php if(isset($errors['name'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['name']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label">Species</label>
                <select name="species" class="form-select <?= isset($errors['species'])?'is-invalid':'' ?>">
                  <?php foreach (['dog','cat'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($old['species']??'')===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['species'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['species']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label">Sex</label>
                <select name="sex" class="form-select <?= isset($errors['sex'])?'is-invalid':'' ?>">
                  <?php foreach (['male','female'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($old['sex']??'')===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['sex'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['sex']) ?></div><?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label">Breed</label>
                <input type="text" name="breed" class="form-control <?= isset($errors['breed'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['breed'] ?? '') ?>">
                <?php if(isset($errors['breed'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['breed']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label">Age (years)</label>
                <input type="number" name="age" class="form-control <?= isset($errors['age'])?'is-invalid':'' ?>"
                       value="<?= htmlspecialchars($old['age'] ?? '') ?>" min="1" max="15">
                <?php if(isset($errors['age'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['age']) ?></div><?php endif; ?>
              </div>
              <div class="col-md-3">
                <label class="form-label">Vaccinated</label>
                <select name="vaccinated" class="form-select <?= isset($errors['vaccinated'])?'is-invalid':'' ?>">
                  <option value="yes" <?= ($old['vaccinated']??'')==='yes'?'selected':'' ?>>Yes</option>
                  <option value="no"  <?= ($old['vaccinated']??'')==='no'?'selected':''  ?>>No</option>
                </select>
                <?php if(isset($errors['vaccinated'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['vaccinated']) ?></div><?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select <?= isset($errors['status'])?'is-invalid':'' ?>">
                  <?php foreach (['available','inactive','adopted'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($old['status']??'')===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if(isset($errors['status'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['status']) ?></div><?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label">Image (optional)</label>
                <label class="dz w-100">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                      <i class="bi bi-cloud-arrow-up"></i>
                      <span>Click to choose a new photo (optional)</span>
                    </div>
                    <span class="badge bg-light text-dark">Optional</span>
                  </div>
                  <input type="file" name="image" accept="image/*"
                         class="form-control <?= isset($errors['image'])?'is-invalid':'' ?>"
                         style="position:absolute; inset:0; opacity:0; cursor:pointer;">
                </label>
                <?php if(isset($errors['image'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['image']) ?></div><?php endif; ?>
                <?php if(!empty($pet['image'])): ?>
                  <div class="small text-muted mt-1">Current: <a href="<?= htmlspecialchars($pet['image']) ?>" target="_blank" rel="noopener">view</a></div>
                <?php endif; ?>
              </div>

              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="4" class="form-control <?= isset($errors['description'])?'is-invalid':'' ?>"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                <?php if(isset($errors['description'])): ?><div class="form-text text-danger"><?= htmlspecialchars($errors['description']) ?></div><?php endif; ?>
              </div>
            </div>

            <div class="mt-4 d-flex gap-2">
              <a href="ngo/pets" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
              <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
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
          <?php
            $hasCurrent = !empty($pet['image']);
            $currentImg = $hasCurrent ? $pet['image'] : '';
          ?>
          <div class="preview-box" id="previewBox" style="<?= $hasCurrent ? 'display:none;' : '' ?>">No image selected</div>
          <img src="<?= $hasCurrent ? htmlspecialchars($currentImg) : '' ?>" class="preview-img mb-3" id="previewImg" alt="Preview" style="<?= $hasCurrent ? 'display:block;' : 'display:none;' ?>">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <div class="fw-bold" id="pvName"><?= htmlspecialchars($old['name'] ?? ($pet['name'] ?? 'Pet Name')) ?></div>
              <div class="text-muted" id="pvMeta">
                <span id="pvSpecies"><?= htmlspecialchars(ucfirst($old['species'] ?? ($pet['species'] ?? 'Species'))) ?></span>
                <span> • </span>
                <span id="pvBreed"><?= htmlspecialchars($old['breed'] ?? ($pet['breed'] ?? 'Breed')) ?></span>
              </div>
            </div>
            <span class="badge-soft" id="pvVacc">
              <?php
                $v = $old['vaccinated'] ?? ($pet['vaccinated'] ?? 'no');
                echo ($v === 'yes') ? 'Vaccinated' : 'Not vaccinated';
              ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    const name    = document.querySelector('input[name="name"]');
    const breed   = document.querySelector('input[name="breed"]');
    const species = document.querySelector('select[name="species"]');
    const vacc    = document.querySelector('select[name="vaccinated"]');
    const file    = document.querySelector('input[name="image"]');

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
  })();
</script>
