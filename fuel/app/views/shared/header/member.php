<header class="topbar">
  <div class="topbar-inner">
    <div class="container">
      <a class="brand" href="/">Colloqueasy</a>
      <ul class="nav">
        <li><?php echo Html::anchor("/students", "Students"); ?></li>
        <li><?php echo Html::anchor("/schools", "Schools"); ?></li>
        <li><?php echo Html::anchor("/companies", "Companies"); ?></li>
        <li><?php echo Html::anchor("/interests", "Interests"); ?></li>
      </ul>
      <ul class="nav secondary-nav">
        <li><p>Welcome back, <strong><?php echo $current_user->first_name; ?></strong>!</p></li>
        <li><?php echo Html::anchor("/students/view/$current_user->id", "Your Profile"); ?></li>
        <li><?php echo Html::anchor("/messages", "Inbox <em>($unread_count)</em>"); ?></li>
        <li><?php echo Html::anchor("/logout", "Logout"); ?></li>
      </ul>
    </div>
  </div>
</header>
