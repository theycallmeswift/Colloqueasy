<div class="well">
  <h2 class="first">Company Search</h2>
  <hr />
  <div class="row">
    <?php echo render('companies/_search_form'); ?>
  </div>
  <hr />
  <h3 class="first">Showing <?php echo count($employees); ?> results</h3>
  <div id="results" class="row">
    <?php foreach($employees as $employee) { ?>
      <div class="span7">
        <div class="span2 fleft">
          <a href="http://www.gravatar.com/avatar/<?php echo md5(strtolower($employee->email)); ?>">
            <img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($employee->email)); ?>?s=80" alt="<?php echo $employee->first_name; ?>'s Gravatar" class="thumbnail">
          </a>
        </div>
        <div class="span5 fleft">
          <h4><?php echo "$employee->first_name $employee->last_name"; ?> <small><?php echo "$employee->position"; ?></small></h4>
          <p><span class="label"><?php echo Date::create_from_string($employee->start_date, '%Y-%m-%d')->format("%B, %Y"); ?></span> to <span class='label'> <?php echo Date::create_from_string($employee->end_date, '%Y-%m-%d')->format("%B, %Y"); ?></span></p>
          <p><?php echo "$employee->name"; ?></p>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
