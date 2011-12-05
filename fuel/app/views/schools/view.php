<div class="well">
  <h2><?php echo $school->name; ?> <small><?php echo "$school->city, $school->state"; ?></small></h2>
  <hr />
  <div class="row">
    <div class="span9">
      <?php if ($education) { ?>
        <h3>You attended <?php echo $school->name; ?></h3><br />
        <strong>Start Date: </strong><?php echo Date::create_from_string($education->start_date, '%Y-%m-%d')->format("%B, %Y"); ?><br />
        <strong>End Date: </strong><?php echo Date::create_from_string($education->end_date, '%Y-%m-%d')->format("%B, %Y"); ?><br />
        <strong>Major: </strong><?php echo $education->major; ?><br />
        <strong>Degree: </strong><?php echo $education->degree; ?><br />


      <?php } else { ?>
      <h3>I attended <?php echo $school->name; ?> in...</h3>
      <?php echo Form::open(); ?>
      <div class="clearfix">
        <label for="start_month">I started in...</label>
        <div class="input">
          <?php echo Form::select('start_month', Input::post('start_month', null), array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
          ), array('class' => 'medium')); ?>
          <?php echo Form::select('start_year', Input::post('start_year', null), array(
            '2000' => '2000',
            '2001' => '2001',
            '2002' => '2002',
            '2003' => '2003',
            '2004' => '2004',
            '2005' => '2005',
            '2006' => '2006',
            '2007' => '2007',
            '2008' => '2008',
            '2009' => '2009',
            '2010' => '2010',
            '2011' => '2011',
            '2012' => '2012',
            '2013' => '2013',
            '2014' => '2014',
            '2015' => '2015',
            '2016' => '2016',
          ), array('class' => 'small')); ?>
        </div>
      </div>
      <div class="clearfix">
        <label for="end_month">I finished in...</label>
        <div class="input">
          <?php echo Form::select('end_month', Input::post('end_month', null), array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
          ), array('class' => 'medium')); ?>
          <?php echo Form::select('end_year', Input::post('end_year', null), array(
            '2000' => '2000',
            '2001' => '2001',
            '2002' => '2002',
            '2003' => '2003',
            '2004' => '2004',
            '2005' => '2005',
            '2006' => '2006',
            '2007' => '2007',
            '2008' => '2008',
            '2009' => '2009',
            '2010' => '2010',
            '2011' => '2011',
            '2012' => '2012',
            '2013' => '2013',
            '2014' => '2014',
            '2015' => '2015',
            '2016' => '2016',
          ), array('class' => 'small')); ?>
        </div>
       </div>

      <div class="clearfix">
        <?php echo Form::label('Major', 'major'); ?>
        <div class="input">
          <?php echo Form::input('major', Input::post('major', '')); ?>
        </div>
      </div>
      <div class="clearfix">
        <?php echo Form::label('Degree', 'degree'); ?>
        <div class="input">
          <?php echo Form::input('degree', Input::post('degree', '')); ?>
        </div>
      </div>
      <div class="actions">
        <?php echo Form::submit(); ?>
      </div>
      <?php echo Form::close(); ?>
      <?php } ?>
    </div>
    <div class="span6 sidebar">
      <div class="well">
      <h4><?php echo count($friends); ?> of your friends attended this school</h4>
        <div class="media-grid">
          <?php foreach($friends as $friend) { ?>
            <?php echo Html::anchor("/students/view/$friend->id", "<img class='thumbnail' src='http://www.gravatar.com/avatar/". md5(strtolower($friend->email))."?s=40' />"); ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <h2><?php echo count($attendees); ?> people have attended this school</h2>
  <div class="media-grid">
    <?php foreach($attendees as $attendee) { ?>
      <?php echo Html::anchor("/students/view/$attendee->id", "<img class='thumbnail' src='http://www.gravatar.com/avatar/". md5(strtolower($attendee->email))."?s=40' />"); ?>
    <?php } ?>
  </div>
  <?php echo Html::anchor('schools', 'Back'); ?>
</div>
