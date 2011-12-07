
<div class="row profile">
  <div class="span5 sidebar">
    <div class="well media-grid">
      <h1><?php echo "$student->first_name $student->last_name"; ?></h1>
      <a href="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>">
        <img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>?s=200" alt="<?php echo $student->first_name; ?>'s Gravatar" class="thumbnail" />
      </a>
      <h6>Menu</h6>
      <hr />
      <?php if(!$owns_profile) { ?>
        <?php if($are_friends) { ?>
          <?php echo Html::anchor("students/remove_friend/$student->id", "Remove $student->first_name as a Friend", array('class' => 'btn large error')); ?>
        <?php } else { ?>
          <?php echo Html::anchor("students/add_friend/$student->id", "Add $student->first_name as a Friend", array('class' => 'btn large success')); ?>
        <?php } ?>
        <?php if($are_in_relationship) { ?>
          <?php echo Html::anchor("students/remove_relationship/$student->id", "End relationship", array('class' => 'btn large error')); ?>
        <?php } else { ?>
          <?php echo Html::anchor("students/add_relationship/$student->id", "Add relationship with $student->first_name", array('class' => 'btn large success')); ?>
        <?php } ?>
        <?php echo Html::anchor("messages/create?receiver_id=$student->id", "Send $student->first_name a Message", array('class' => 'btn large info')); ?>
      <?php } else { ?>
        <?php echo Html::anchor('students/edit/'.$student->id, 'Edit your Profile', array('class' => 'btn large')); ?>
        <?php echo Html::anchor('students/delete/'.$student->id, 'Delete your Profile', array('class' => 'btn large danger','onclick' => "return confirm('Are you sure?')")); ?>
      <?php } ?>
    </div>
  </div>
  <div class="span10 main">
    <div class="well">
      <ul class="tabs" data-tabs="tabs">
        <li class="active"><a href="#overview">Overview</a></li>
        <li class=""><a href="#education">Eduction</a></li>
        <li class=""><a href="#work">Work</a></li>
        <li class=""><a href="#interests">Interests</a></li>
      </ul>

      <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="overview">
          <ul class="unstyled">
            <li><strong>Gender:</strong> <?php echo $student->gender; ?></li>
	    <?php $in_relationship=false; ?>
            <?php foreach($relationships as $relationship) {$in_relationship=true; ?>
              <li><strong><?php echo $student->relationship_status ?> with:</strong> <?php echo Html::anchor("students/view/$relationship->acceptor_id", "$relationship->first_name $relationship->last_name"); ?></li>
            <?php } ?>
	    <?php if($in_relationship==false){ echo $student->relationship_status; } ?>
          </ul>
	  
	  <strong>Date of Birth:</strong>
	  <p><?php echo $student->DoB ?></p>
	  
	  <strong>Address:</strong>
	  <p><?php echo $student->address ?></p>
	  
          <strong>Bio:</strong>
          <p><?php echo $student->bio ?></p>

          <h4><?php echo $student->first_name ?> has <?php echo count($friends); ?> friends:</h4>
          <div class="media-grid">
            <?php foreach($friends as $friend) { ?>
              <?php echo Html::anchor("/students/view/$friend->id", "<img class='thumbnail' src='http://www.gravatar.com/avatar/". md5(strtolower($friend->email))."?s=40' />"); ?>
            <?php } ?>
          </div>
        </div>
        <div class="tab-pane" id="education">
          <?php foreach($schools as $school) { ?>
            <h4><?php echo Html::anchor("schools/view/$school->id", $school->name); ?> <small><?php echo "$school->city, $school->state"; ?></small></h4>
            <p>Between <span class="label"><?php echo Date::create_from_string($school->start_date, '%Y-%m-%d')->format("%B, %Y"); ?></span> and <span class='label'> <?php echo Date::create_from_string($school->end_date, '%Y-%m-%d')->format("%B, %Y"); ?></span></p>
            <p><?php echo $student->first_name ?> earned a <?php echo $school->degree; ?> in <?php echo $school->major; ?></p>
            <hr />
          <?php } ?>
        </div>
        <div class="tab-pane" id="work">
          <?php foreach($companies as $company) { ?>
            <h4><?php echo Html::anchor("companies/view/$company->id", $company->name); ?> <small><?php echo "$company->city, $company->state"; ?></small></h4>
            <p>Between <span class="label"><?php echo Date::create_from_string($company->start_date, '%Y-%m-%d')->format("%B, %Y"); ?></span> and <span class='label'> <?php echo Date::create_from_string($company->end_date, '%Y-%m-%d')->format("%B, %Y"); ?></span></p>
            <p><?php echo $student->first_name ?> worked as an <?php echo $company->position; ?></p>
            <hr />
          <?php } ?>
        </div>
        <div class="tab-pane" id="interests">
          <?php foreach($interests as $interest) { ?>
            <div>
              <h4><?php echo $interest->name; ?></h4>
              <p><?php echo $interest->description; ?></p>
              <hr />
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo Html::anchor('students', 'Back'); ?>
