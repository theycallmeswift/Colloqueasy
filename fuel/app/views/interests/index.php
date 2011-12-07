<div class="well">
  <div class="row">
    <div class="span9">
      <h2 class="first">Browse Interests</h2>
    </div>
    <div class="span6">
      <p>Have an interest not listed here?  <?php echo Html::anchor('interests/create', 'Create one!', array("class" => "btn large success")); ?></p>
    </div>
  </div>
  <hr />
  <div class="span16">
    <h3>Filter Results <small>Separate multiple terms with commas</small></h3>
    <?php echo Form::open(array('method' => 'get')); ?>
      <div class="clearfix">
        <?php echo Form::label('Search Interests', 'search'); ?>
        <div class="input">
          <?php echo Form::input('search', Input::get('search', ''), array('class' => 'span7')); ?>
          <input type="checkbox" name="friends_only" value="1" <?php echo (Input::get('friends_only', '0') == '1') ? 'checked' : ''; ?>>
          <span>only my friends</span>
          <?php echo Form::submit('submit', 'Search', array('class' => 'span3 btn primary')); ?>
        </div>
      </div>
    <?php echo Form::close(); ?>
  </div>
  <?php if ($interests): ?>
    <hr />
    <div id="results" class="row">
      <?php foreach($interests as $interest) { ?>
        <div class="span15">
          <h4 class="fleft"><?php echo $interest->name; ?><?php echo $friends_only ? " <small>$interest->friend_count friends</small>" : ""; ?></h4>
          <?php if($interest->is_interested) { ?>
            <?php echo Html::anchor("interests/remove_interest/$interest->id", "Remove interest", array('class' => 'fright btn error')); ?>
          <?php } else { ?>
            <?php echo Html::anchor("interests/add_interest/$interest->id", "Add interest", array('class' => 'fright btn success')); ?>
          <?php } ?>
          <br />
          <br />
          <p><?php echo $interest->description; ?></p>
          <hr />
        </div>
      <?php } ?>
    </div>
  <?php else: ?>
    <p>No Entries.</p>
  <?php endif; ?>
</div>
