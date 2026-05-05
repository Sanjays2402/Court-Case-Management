<?php
/**
 * Shared form fields used by addcase.php and case_edit.php.
 * Expects $f (associative array of field values) in scope.
 */
$priorities = ['low','medium','high','urgent'];
$statuses   = ['open','in_progress','adjourned','closed','dismissed','won','lost'];
?>
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Case type *</label>
    <input name="case_type" class="form-control" required
           placeholder="Civil / Criminal / Family..."
           value="<?= ccms_e($f['case_type'] ?? '') ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Case number *</label>
    <input name="case_number" class="form-control" required
           placeholder="e.g. AC235417"
           value="<?= ccms_e($f['case_number'] ?? '') ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">FIR number</label>
    <input name="fir_number" class="form-control"
           value="<?= ccms_e($f['fir_number'] ?? '') ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Advocate name *</label>
    <input name="advocate_name" class="form-control" required
           value="<?= ccms_e($f['advocate_name'] ?? '') ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Advocate ID</label>
    <input name="advocate_id" class="form-control"
           value="<?= ccms_e($f['advocate_id'] ?? '') ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Filed on *</label>
    <input name="filed_on" type="date" class="form-control" required
           value="<?= ccms_e($f['filed_on'] ?? date('Y-m-d')) ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Next hearing</label>
    <input name="next_hearing" type="date" class="form-control"
           value="<?= ccms_e($f['next_hearing'] ?? '') ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label">Priority</label>
    <select name="priority" class="form-select">
      <?php foreach ($priorities as $p): ?>
        <option value="<?= $p ?>" <?= ($f['priority'] ?? 'medium') === $p ? 'selected' : '' ?>>
          <?= ucfirst($p) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <?php foreach ($statuses as $s): ?>
        <option value="<?= $s ?>" <?= ($f['status'] ?? 'open') === $s ? 'selected' : '' ?>>
          <?= ucfirst(str_replace('_',' ', $s)) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"
              placeholder="Brief facts of the case..."><?= ccms_e($f['description'] ?? '') ?></textarea>
  </div>
</div>
