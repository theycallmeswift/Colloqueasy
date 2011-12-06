<div class="well">
  <h2 class="first">School Search</h2>
  <hr />
  <div class="row">
    <?php echo render('schools/_search_form'); ?>
  </div>
  <hr />
  <h3 class="first">Showing <?php echo count($students); ?> results</h3>
  <div id="results" class="row">
    <?php foreach($students as $student) { ?>
      <div class="span7">
        <div class="span2 fleft">
          <a href="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>">
            <img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>?s=80" alt="<?php echo $student->first_name; ?>'s Gravatar" class="thumbnail">
          </a>
        </div>
        <div class="span5 fleft">
          <h4><?php echo "$student->first_name $student->last_name"; ?> <small><?php echo "$student->major, $student->degree"; ?></small></h4>
          <p><span class="label"><?php echo Date::create_from_string($student->start_date, '%Y-%m-%d')->format("%B, %Y"); ?></span> to <span class='label'> <?php echo Date::create_from_string($student->end_date, '%Y-%m-%d')->format("%B, %Y"); ?></span></p>
          <p><?php echo "$student->name"; ?></p>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
