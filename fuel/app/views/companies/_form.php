<?php echo Form::open(); ?>
	<p>
		<?php echo Form::label('Name', 'name'); ?>
<?php echo Form::input('name', Input::post('name', isset($company) ? $company->name : '')); ?>
	</p>
	<p>
		<?php echo Form::label('City', 'city'); ?>
<?php echo Form::input('city', Input::post('city', isset($company) ? $company->city : '')); ?>
	</p>
	<p>
		<?php echo Form::label('State', 'state'); ?>
<?php echo Form::input('state', Input::post('state', isset($company) ? $company->state : '')); ?>
	</p>

	<div class="actions">
		<?php echo Form::submit(); ?>	</div>

<?php echo Form::close(); ?>
