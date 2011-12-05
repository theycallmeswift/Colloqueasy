<?php echo Form::open(); ?>
	<p>
		<?php echo Form::label('Name', 'name'); ?>
<?php echo Form::input('name', Input::post('name', isset($school) ? $school->name : '')); ?>
	</p>
	<p>
		<?php echo Form::label('City', 'city'); ?>
<?php echo Form::input('city', Input::post('city', isset($school) ? $school->city : '')); ?>
	</p>
	<p>
		<?php echo Form::label('State', 'state'); ?>
<?php echo Form::input('state', Input::post('state', isset($school) ? $school->state : '')); ?>
	</p>

	<div class="actions">
		<?php echo Form::submit(); ?>	</div>

<?php echo Form::close(); ?>