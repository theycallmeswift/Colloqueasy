<?php echo Form::open(); ?>
  <p>
    <?php echo Form::label('Name', 'name'); ?>
<?php echo Form::input('name', Input::post('name', isset($interest) ? $interest->name : ''), array('class' => 'span6')); ?>
  </p>
  <p>
    <?php echo Form::label('Description', 'description'); ?>
<?php echo Form::textarea('description', Input::post('description', isset($interest) ? $interest->description: ''), array('class' => 'span6')); ?>
  </p>

  <div class="actions">
    <?php echo Form::submit(); ?> </div>

<?php echo Form::close(); ?>
