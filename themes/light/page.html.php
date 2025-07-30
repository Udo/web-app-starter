<?php
	Profiler::log('page template: start', 1);
?><!doctype html>
<html class="no-js" lang="">
<?php
	$url_root = cfg('url/root');
	$theme_path = cfg('theme/path');
	$theme_path_common = 'themes/common/';
?>
<head>
	<meta charset="utf-8">
	<title><?= first(URL::$route['page-title'], cfg('site/default_page_title')).' | '.cfg('site/name') ?></title>
	<meta name="description" content="Modern PHP web application framework with beautiful components and responsive design">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#3b82f6">
	
	<!-- Preconnect to external domains for better performance -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<link rel="apple-touch-icon" href="<?= $theme_path ?>icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= $theme_path ?>icon.png">

	<?php
		include_css($theme_path.'css/style.css');
		include_css($theme_path_common.'fontawesome/css/all.min.css');
		include_js('js/uquery.js');
		include_js('js/site.js');
		include_js('js/modern-enhancements.js');
	?>
</head>

<body>
	<nav>
		<a href="<?= URL::link('') ?>"><?= cfg('site/name') ?></a><?php
		foreach(cfg('menu') as $mk => $menu_item) if(!$menu_item['hidden'])	
		{
			?><a href="<?= URL::link($mk) ?>"><?= htmlspecialchars($menu_item['title']) ?></a><?
		}
		?>
	</nav>
	<div id="content">
		<?= URL::$fragments['main'] ?> 
	</div> 
	<footer>
		<div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
			<p>WebAppStarter by Udo Schroeter (udo@openfu.com)</p>
			<p style="font-size: 0.875rem; margin-top: 0.5rem; opacity: 0.7;">
				Built with modern web technologies and love for developers
			</p>
		</div>
	</footer>
</body>
<?php
	Profiler::log('page template: end', -1);
?>
<script>
	console.log('server stats', <?= json_encode([
		'route' => URL::$route,
		'perf' => Profiler::$log,
	]) ?>);
</script>
</html>

