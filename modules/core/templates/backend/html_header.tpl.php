<!DOCTYPE html>
<!--[if IEMobile 7]><html class="iem7"  lang="<?php echo get_language(); ?>" dir="ltr"><![endif]-->
<!--[if lte IE 6]><html class="lt-ie9 lt-ie8 lt-ie7"  lang="<?php echo get_language(); ?>" dir="ltr"><![endif]-->
<!--[if (IE 7)&(!IEMobile)]><html class="lt-ie9 lt-ie8"  lang="<?php echo get_language(); ?>" dir="ltr"><![endif]-->
<!--[if IE 8]><html class="lt-ie9"  lang="<?php echo get_language(); ?>" dir="ltr"><![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)]><!--><html lang="<?php echo get_language(); ?>" dir="ltr"><!--<![endif]-->

<head profile="http://www.w3.org/1999/xhtml/vocab">
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="HosterDIY content delivery team">
  <link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
  
  <title><?php echo $title; ?></title>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript">
  var site = jQuery();
  site.settings = <?php echo HTML::renderSettingsInJson() ?>;
  site.settings.subroot = '<?php echo get_sub_root(); ?>';
</script>
<?php HTML::renderOutHeaderUpperRegistry(); ?>  
<?php Asset::printTopAssets('backend'); ?>
<?php HTML::renderOutHeaderLowerRegistry(); ?>
  
</head>

<body class="admin <?php if (isset($body_class)) {echo $body_class; }?>">

