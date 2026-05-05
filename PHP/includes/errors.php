<?php
/** Render server-side validation errors. Expects $errors array in scope. */
if (!empty($errors)):
?>
<div class="alert alert-danger">
  <ul class="mb-0 ps-3">
    <?php foreach ($errors as $err): ?>
      <li><?= htmlspecialchars((string) $err, ENT_QUOTES) ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
