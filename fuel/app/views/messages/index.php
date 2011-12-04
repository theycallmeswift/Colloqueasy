<h2 class="first fleft">Listing Messages</h2>

<?php echo Html::anchor('messages/create', 'Send a Message', array('class' => 'btn large success fright')); ?>
<div class="clear">&nbsp;</div>
<hr />

<div class="well">
  <ul class="tabs" data-tabs="tabs">
    <li class="active"><a href="#inbox">Inbox</a></li>
    <li class=""><a href="#sent">Sent</a></li>
  </ul>

  <div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="inbox">
      <?php if ($messages): ?>
      <h3>Received Messages</h3>
      <table cellspacing="0">
        <tr>
          <th>From</th>
          <th>Subject</th>
          <th>Date</th>
          <th></th>
        </tr>

        <?php foreach ($messages as $message): ?> <tr>

          <td><?php echo \Model\Student::find_one_by_id($message->sender_id)->first_name; ?></td>
          <td>
            <?php if ($message->has_read == 0) { ?><strong><?php } ?>
            <?php echo $message->subject; ?></td>
            <?php if ($message->has_read == 0) { ?></strong><?php } ?>
          <td><?php echo Date::time_ago($message->date); ?></td>
          <td>
            <?php echo Html::anchor('messages/view/'.$message->id, 'View'); ?> |
            <?php echo Html::anchor('messages/delete/'.$message->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

      <?php else: ?>
        <p>No Entries.</p>
      <?php endif; ?>
    </div>
    <div class="tab-pane" id="sent">
      <?php if ($sent_messages): ?>
      <h3>Sent Messages</h3>
      <table cellspacing="0">
        <tr>
          <th>To</th>
          <th>Subject</th>
          <th>Date</th>
          <th></th>
        </tr>

        <?php foreach ($sent_messages as $message): ?> <tr>

          <td><?php echo \Model\Student::find_one_by_id($message->receiver_id)->first_name; ?></td>
          <td><?php echo $message->subject; ?></td>
          <td><?php echo Date::time_ago($message->date); ?></td>
          <td>
            <?php echo Html::anchor('messages/view/'.$message->id, 'View'); ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

      <?php else: ?>
        <p>No Entries.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

