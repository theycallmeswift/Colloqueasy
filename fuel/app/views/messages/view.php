<?php $student = \Model\Student::find_one_by_id($message->sender_id); ?>
<div class="row well">
  <div class="span3 media-grid">
    <a href="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>">
      <img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>?s=100" alt="<?php echo $student->first_name; ?>'s Gravatar" class="thumbnail" />
    </a>
  </div>
  <div class="span12">
    <?php $re_subject = urlencode("Re: $message->subject"); ?>
    <?php echo Html::anchor("messages/create?receiver_id=$message->sender_id&subject=$re_subject", "Reply", array("class" => "btn large success fright")); ?>
    <h4><strong>From:</strong> <?php echo "$student->first_name $student->last_name"; ?>
        <small><?php echo Date::time_ago($message->date); ?></small></h4>
    <hr />
  </div>
  <div class="clear"></div>
  <h3><?php echo $message->subject; ?></h3>
  <p><?php echo $message->body; ?></p>
  <?php echo Html::anchor('messages', 'Back'); ?>
<div>
