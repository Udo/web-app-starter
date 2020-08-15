<!doctype html>
<html class="no-js" lang="">
<?php
	$url_root = cfg('url/root');
	$theme_path = $url_root.cfg('theme/path');
?>
<head>
  <meta charset="utf-8">
  <title><?= first(URL::$route['page-title'], cfg('site/default_page_title')).' | '.cfg('site/name') ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="<?= $theme_path ?>icon.png">
  <script src="<?= $url_root ?>js/jquery.js"></script>

  <link rel="stylesheet" href="<?= $theme_path ?>css/">

  <meta name="theme-color" content="#fafafa">
</head>

<body>
	<nav>
		<?= component('account/button') ?>
		<a href="<?= URL::link('/') ?>"><?= cfg('site/name') ?></a><?php
		foreach(cfg('menu') as $mk => $menu_item) if(!$menu_item['hidden'])	
		{
			?><a href="<?= URL::link($mk) ?>"><?= htmlspecialchars($menu_item['title']) ?></a><?
		}
		?>
	</nav>
	<div id="content">
		<?= ($content) ?> 
	</div> 
	<footer>
		WebAppStarter by Udo Schroeter (udo@openfu.com)
	</footer>
</body>

</html>
