<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $title; ?></title>
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <?php echo Asset::render('bootstrap_css'); ?>
  <?php echo Asset::render('custom_styles'); ?>
  <?php echo Asset::render('jquery'); ?>
</head>
<body>
  <?php echo $header; ?>
  <div class="container">
    <?php echo $flash ?>

    <?php echo $content; ?>
  </div>
  <?php echo $footer; ?>
  <?php echo Asset::render('bootstrap_js'); ?>
</body>
</html>
