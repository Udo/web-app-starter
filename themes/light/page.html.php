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
	
	<link rel="apple-touch-icon" href="<?= $theme_path ?>icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= $theme_path ?>icon.png">

	<?php
		include_css($theme_path.'css/style.css');
		include_css($theme_path_common.'fontawesome/css/all.min.css');
		include_js('js/u-query.js');
		include_js('js/morphdom.js');
		include_js('js/site.js');
	?>
</head>

<body>
	<?= component('components/example/theme-switcher') ?>
	<?= component('components/basic/cookie-consent') ?>
	<nav>
		<?php require_once __DIR__ . '/../../lib/user.class.php'; ?>
		<div class="nav-menu">
			<a href="<?= URL::link('') ?>"><?= cfg('site/name') ?></a>
			<?php
			foreach(cfg('menu') as $mk => $menu_item) if(!$menu_item['hidden'])	
			{
				?><a href="<?= URL::link($mk) ?>"><?= safe($menu_item['title']) ?></a><?php
			}
			?>
		</div>
		<?php
		if (User::IsSignedIn()) {
			$__u = User::$current_profile;
			?>
			<div class="nav-account">
				<span class="account-name"><?php echo htmlspecialchars($__u['email'] ?? 'Account'); ?></span>
				<a href="<?= URL::link('account/profile') ?>">Profile</a>
				<a href="<?= URL::link('account/logout') ?>">Logout</a>
			</div>
			<?php
		} else {
			?>
			<div class="nav-account">
				<a href="<?= URL::link('account/login') ?>">Login</a>
				<a href="<?= URL::link('account/register') ?>">Register</a>
			</div>
			<?php
		}
		?>
	</nav>
	<div id="content">
		<?= URL::$fragments['main'] ?> 
	</div> 
	<footer>
		<div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
			<p>WebAppStarter by Udo Schroeter (udo@openfu.com)</p>
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

