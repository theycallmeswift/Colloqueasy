<?php if (Session::get_flash('error')): ?>
  <div class="alert-message error" data-alert="alert">
    <a class="close" href="#">&times;</a>
    <p><?php echo implode('</p><p>', (array) Session::get_flash('error')); ?></p>
  </div>
<?php endif; ?>

<?php if (Session::get_flash('success')): ?>
  <div class="alert-message success" data-alert="alert">
    <a class="close" href="#">&times;</a>
    <p><?php echo implode('</p><p>', (array) Session::get_flash('success')); ?></p>
  </div>
<?php endif; ?>

<?php if (Session::get_flash('warning')): ?>
  <div class="alert-message warning" data-alert="alert">
    <a class="close" href="#">&times;</a>
    <p><?php echo implode('</p><p>', (array) Session::get_flash('warning')); ?></p>
  </div>
<?php endif; ?>

<?php if (Session::get_flash('info')): ?>
  <div class="alert-message info" data-alert="alert">
    <a class="close" href="#">&times;</a>
    <p><?php echo implode('</p><p>', (array) Session::get_flash('info')); ?></p>
  </div>
<?php endif; ?>
