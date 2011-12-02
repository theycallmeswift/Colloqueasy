<h2 class="first">Editing Student</h2>

<?php echo render('students/_form'); ?>
<br />
<p>
<?php echo Html::anchor('students/view/'.$student->id, 'View'); ?> |
<?php echo Html::anchor('students', 'Back'); ?></p>
