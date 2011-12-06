<div class="span16">
  <h4>Advanced Search <small>Separate multiple values with commas</small></h4>
</div>
<?php echo Form::open(array('method' => 'get')); ?>
<div class="span16">
  <div class="span7 fleft">
    <div class="clearfix">
      <?php echo Form::label('Name', 'name'); ?>
      <div class="input">
        <?php echo Form::input('name', Input::get('name', ''), array('class' => 'xLarge')); ?>
      </div>
    </div>

    <div class="clearfix">
      <?php echo Form::label('City', 'city'); ?>
      <div class="input">
        <?php echo Form::input('city', Input::get('city', ''), array('class' => 'xLarge')); ?>
      </div>
    </div>

    <div class="clearfix">
      <?php echo Form::label('State', 'state'); ?>
      <div class="input">
        <?php echo Form::input('state', Input::get('state', ''), array('class' => 'xLarge')); ?>
      </div>
    </div>
  </div>
  <div class="span7 fleft">
    <div class="clearfix">
      <?php echo Form::label('Degree', 'degree'); ?>
      <div class="input">
        <?php echo Form::input('degree', Input::get('degree', ''), array('class' => 'xLarge')); ?>
      </div>
    </div>

    <div class="clearfix">
      <?php echo Form::label('Major', 'major'); ?>
      <div class="input">
        <?php echo Form::input('major', Input::get('major', ''), array('class' => 'xLarge')); ?>
      </div>
    </div>

    <div class="clearfix">
      <div class="input">
        <ul class="inputs-list">
          <li>
            <label>
            <input type="checkbox" name="friends_only" value="1" <?php echo (Input::get('friends_only', '0') == '1') ? 'checked' : ''; ?>>
              <span>Search only my friends</span>
            </label>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<div class="span16">
  <div class="actions">
    <?php echo Form::submit(); ?>
  </div>
</div>
<?php echo Form::close(); ?>
