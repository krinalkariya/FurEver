<div class="container py-3">
  <a class="btn btn-sm btn-link mb-2" href="user/pets">&larr; Back to list</a>

  <div class="row g-3">
    <div class="col-12 col-md-5">
      <img src="<?php echo htmlspecialchars($pet['image']); ?>" class="img-fluid rounded" alt="pet">
    </div>
    <div class="col-12 col-md-7">
      <h3 class="mb-1"><?php echo htmlspecialchars($pet['name']); ?></h3>
      <div class="text-muted mb-2">
        <?php echo htmlspecialchars($pet['species'] . ' • ' . $pet['breed']); ?> |
        <?php echo htmlspecialchars($pet['ngo_city']); ?> |
        <?php echo (int)$pet['age']; ?> years
      </div>

      <div class="mb-2">
        <span class="badge text-bg-secondary"><?php echo htmlspecialchars(ucfirst($pet['sex'])); ?></span>
        <span class="badge text-bg-<?php echo $pet['vaccinated']==='yes'?'success':'warning'; ?>">
          <?php echo $pet['vaccinated']==='yes'?'Vaccinated':'Not Vaccinated'; ?>
        </span>
        <span class="badge text-bg-<?php
          echo $pet['status']==='available'?'success':($pet['status']==='adopted'?'secondary':'dark');
        ?>">
          <?php echo ucfirst($pet['status']); ?>
        </span>
      </div>

      <p class="mb-3"><?php echo nl2br(htmlspecialchars($pet['description'])); ?></p>

      <?php if ($pet['status'] === 'available'): ?>
        <form method="post" action="user/adopt?id=<?php echo (int)$pet['pet_id']; ?>">
          <?php echo CSRF::input(); ?>
          <?php if ($pet['status'] !== 'available'): ?>
  <div class="alert alert-secondary">This pet is no longer available for adoption.</div>
<?php elseif ($applied): ?>
  <div class="alert alert-info">
    You have already applied for this pet. Status: <strong><?= htmlspecialchars($applied['status']) ?></strong>
  </div>
<?php else: ?>
  <form method="POST" action="user/adopt?id=<?= $pet['pet_id'] ?>">
    <?= CSRF::input() ?>
    <button class="btn btn-success">Apply to Adopt</button>
  </form>
<?php endif; ?>
        </form>
      <?php else: ?>
        <div class="alert alert-secondary mb-0">This pet is not available for adoption.</div>
      <?php endif; ?>

      <div class="mt-3 small text-muted">Listed by: <?php echo htmlspecialchars($pet['ngo_name']); ?> (<?php echo htmlspecialchars($pet['ngo_city']); ?>)</div>
    </div>
  </div>
</div>
