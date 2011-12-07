<div class="well">
  <div class="row">
    <div class="span7">
      <h2 class="first">All Companies</h2>
    </div>
    <div class="span8">
      <p>Don't see your company? <?php echo Html::anchor('companies/search', 'Search', array("class" => "btn large primary")); ?> <?php echo Html::anchor('companies/create', 'Add your company', array("class" => "btn large success")); ?></p>
    </div>
  </div>

  <?php if ($companies): ?>
    <?php $companies_displayed = 0; ?>

    <?php foreach($state_counts as $state_count) { ?>
      <div class="state_count">
        <div class="row title">
          <div class="span12">
            <h3><?php echo $state_count->state ?> <small>(<?php echo $state_count->count; ?> companies)</small></h3>
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

          <?php for($i = $companies_displayed; $i < ($companies_displayed + $state_count->count); $i++) { ?>
          <tr>

            <td> <?php echo Html::anchor('companies/view/'.$companies[$i]->id, $companies[$i]->name); ?></td>
            <td><?php echo $companies[$i]->city; ?></td>
            <td><?php echo $companies[$i]->state; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php $companies_displayed += $state_count->count; ?>
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
