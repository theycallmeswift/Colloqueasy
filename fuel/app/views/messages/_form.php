<?php echo Form::open(); ?>
  <div class="clearfix">
    <?php echo Form::label('Receiver id', 'receiver_id'); ?>
    <div class="input">
      <?php echo Form::select('receiver_id', Input::get_post('receiver_id', isset($message) ? $message->receiver_id : ''), $students); ?>
    </div>
  </div>

  <div class="clearfix">
    <?php echo Form::label('Subject', 'subject'); ?>
    <div class="input">
      <?php echo Form::input('subject', Input::get_post('subject', isset($message) ? $message->subject : '')); ?>
    </div>
  </div>

  <div class="clearfix">
    <?php echo Form::label('Body', 'body'); ?>
    <div class="input">
      <?php echo Form::textarea('body', Input::post('body', isset($message) ? $message->body : ''), array('cols' => 60, 'rows' => 8)); ?>
    </div>
  </div>

  <div class="actions">
    <?php echo Form::submit(); ?>
  </div>

<?php echo Form::close(); ?>
