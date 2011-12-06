<div class="well">
  <div class="row">
    <div class="span9">
      <h2 class="first">All Students</h2>
    </div>
    <div class="span6">
      <p>Don't have a profile?  <?php echo Html::anchor('students/create', 'Create one!', array("class" => "btn large success")); ?></p>
    </div>
  </div>
  <?php if ($students): ?>
    <hr />
    <div id="results" class="row">
      <?php foreach($students as $student) { ?>
        <div class="span5">
          <div class="span2 fleft">
          <?php echo Html::anchor("students/view/$student->id", 
                "<img src='http://www.gravatar.com/avatar/".md5(strtolower($student->email))."?s=80' alt='$student->first_name\'s Gravatar' class='thumbnail'>"); ?>
          </div>
          <div class="span3 fleft">
            <h4><?php echo Html::anchor("students/view/$student->id", "$student->first_name $student->last_name"); ?> <small><?php echo $student->gender; ?></small></h4>
            <p><?php echo $student->friend_count; ?> friends</p>
          </div>
        </div>
      <?php } ?>
    </div>
  <?php else: ?>
    <p>No Entries.</p>
  <?php endif; ?>
</div>
