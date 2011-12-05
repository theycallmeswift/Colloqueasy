<div class="well">
  <div class="row">
    <div class="span9">
      <h2 class="first">All Schools</h2>
    </div>
    <div class="span6">
      <p>Don't see your school?  <?php echo Html::anchor('schools/create', 'Add your School', array("class" => "btn large success")); ?></p>
    </div>
  </div>

  <?php if ($schools): ?>
    <?php $schools_displayed = 0; ?>

    <?php foreach($state_counts as $state_count) { ?>
      <div class="state_count">
        <div class="row title">
          <div class="span12">
            <h3><?php echo $state_count->state ?> <small>(<?php echo $state_count->count; ?> schools)</small></h3>
          </div>
          <div class="span2" style="margin-top: 5px">
            <span class="label notice">click to show</span>
            <span style="display: none;" class="label important">click to hide</span>
          </div>
        </div>
        <table cellspacing="0" class="zebra-striped" style="display: none;">
          <tr>
            <th>Name</th>
            <th>City</th>
            <th>State</th>
          </tr>

          <?php for($i = $schools_displayed; $i < ($schools_displayed + $state_count->count); $i++) { ?>
          <tr>

            <td> <?php echo Html::anchor('schools/view/'.$schools[$i]->id, $schools[$i]->name); ?></td>
            <td><?php echo $schools[$i]->city; ?></td>
            <td><?php echo $schools[$i]->state; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php $schools_displayed += $state_count->count; ?>
    <?php } ?>
  <?php else: ?>
  <p>No Entries.</p>

  <?php endif; ?>
  <br />
</div>

<script>
  $(document).ready(function() {
    $('.state_count').hover(function() {
      $(this).children('.title').css('background-color', '#DDDDDD');
    },
    function() {
      $(this).children('.title').css('background-color', '#FFFFFF');
    });

    $('.state_count').click(function() {
      $(this).find('.label').toggle();
      $(this).children('.zebra-striped').toggle();
    });
  });
</script>
