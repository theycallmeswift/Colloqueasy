<?php echo Form::open(); ?>
  <p>
    <?php echo Form::label('First name', 'first_name'); ?>
    <?php echo Form::input('first_name', Input::post('first_name', isset($student) ? $student->first_name : '')); ?>
  </p>
  <p>
    <?php echo Form::label('Last name', 'last_name'); ?>
    <?php echo Form::input('last_name', Input::post('last_name', isset($student) ? $student->last_name : '')); ?>
  </p>
  <p>
    <?php echo Form::label('Email', 'email'); ?>
    <?php echo Form::input('email', Input::post('email', isset($student) ? $student->email : '')); ?>
  </p>
  <p>
    <?php echo Form::label('Gender', 'gender'); ?>
    <?php echo Form::select('gender',
                            Input::post('gender', isset($student) ? $student->gender : ''),
                            array(
                              'male' => 'Male',
                              'female' => 'Female'
                            ));
    ?>
  </p>
  <p>
    <?php echo Form::label('Bio', 'bio'); ?>
    <?php echo Form::textarea('bio', Input::post('bio', isset($bio) ? $bio: '')); ?>
  </p>
  
  <p>
  	<?php echo Form::label('Date Of Birth:', 'DoB'); ?>
	<?php echo Form::input('DoB', Input::post('DoB', isset($student) ? $student->DoB : '')); ?>
  </p>

  <p>
  	<?php echo Form::label('Address:', 'address'); ?>
	<?php echo Form::input('address', Input::post('address', isset($student) ? $student->address : '')); ?>
  </p>
  
  <p>
  	<?php echo Form::label('Relationship Status', 'relationship_status'); ?>
	<?php echo Form::input('relationship_status', Input::post('relationship_status', isset($student) ? $student->relationship_status : '')); ?>
  </p>
<div class="actions">
    <?php echo Form::submit(); ?>
 </div>

<?php echo Form::close(); ?>
