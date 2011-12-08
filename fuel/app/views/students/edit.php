<div class="well">
  <h2 class="first">Edit your Profile</h2>

      <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="basic">
          <?php echo render('students/_edit_form'); ?>
        </div>
      </div>
</div>
<p>
  <?php echo Html::anchor('students/view/'.$student->id, 'View'); ?> |
  <?php echo Html::anchor('students', 'Back'); ?>
</p>
