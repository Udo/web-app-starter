<?php
	Profiler::log('page template: start', 1);
	$url_root = cfg('url/root');
	if(!$url_root) $url_root = '/';
	if(substr($url_root, -1) !== '/') $url_root .= '/';
	$embed_mode = !empty($_GET['embed']);
	$theme_path = cfg('theme/path');
	$theme_path_common = 'themes/common/';
	$themeModeClass = cfg('theme/mode') === 'dark' ? ' dark-theme' : '';
	require_once __DIR__ . '/../../lib/user.class.php';
?><!doctype html>
<html class="no-js<?= $themeModeClass ?>" lang="en" data-theme-key="<?= asafe((string)cfg('theme/key')) ?>">
<head>
	<meta charset="utf-8">
	<title><?= first(URL::$route['page-title'], cfg('site/default_page_title')).' | '.cfg('site/name') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#0f68ad">
	<link rel="apple-touch-icon" href="<?= $url_root.ltrim($theme_path, '/') ?>icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= $url_root.ltrim($theme_path, '/') ?>icon.png">
	<?php
		include_css($theme_path.'css/style.css');
		include_css($theme_path_common.'fontawesome/css/all.min.css');
		include_js('js/u-query.js');
		include_js('js/morphdom.js');
		include_js('js/site.js');
	?>
</head>
<body class="portal-theme portal-light-theme<?= $embed_mode ? ' embed-mode' : '' ?>">
	<?php if(!$embed_mode): ?><?= component('components/example/theme-switcher') ?><?php endif; ?>
	<?php if(!$embed_mode): ?><?= component('components/basic/cookie-consent') ?><?php endif; ?>
	<nav>
		<div class="nav-menu">
			<a href="<?= URL::link('') ?>"><?= cfg('site/name') ?></a>
			<?php foreach(cfg('menu') as $mk => $menu_item) if(!$menu_item['hidden']) { $href = !empty($menu_item['external']) ? '/'.ltrim($mk, '/') : URL::link($mk); ?><a href="<?= $href ?>"><?= safe($menu_item['title']) ?></a><?php } ?>
		</div>
		<?php if (User::IsSignedIn()) { $__u = User::$current_profile; $__avatar = first($__u['avatar_url'] ?? false, $__u['profile_image'] ?? false); ?>
			<div class="nav-account">
				<?php if($__avatar): ?><img src="<?= safe($__avatar) ?>" alt="Profile" class="nav-avatar" onerror="this.style.display='none';"><?php endif; ?>
				<span class="account-name"><?= safe((string)first($__u['username'] ?? false, $__u['email'] ?? false, 'Account')) ?></span>
				<a href="<?= URL::link('account/profile') ?>">Profile</a>
				<a href="<?= URL::link('account/logout') ?>">Logout</a>
			</div>
		<?php } else { ?>
			<div class="nav-account">
				<a href="<?= URL::link('account/login') ?>">Login</a>
				<?php if(cfg('users/enable_signup')): ?><a href="<?= URL::link('account/register') ?>">Register</a><?php endif; ?>
			</div>
		<?php } ?>
	</nav>
	<div id="content"<?= $embed_mode ? ' class="embed-content"' : '' ?>>
		<?= URL::$fragments['main'] ?>
	</div>
	<?php if(!$embed_mode): ?><footer>
		<div class="footer-inner">
			<p><?= safe(cfg('site/name')) ?> running with the AI Portal Light starter theme.</p>
		</div>
	</footer><?php endif; ?>
</body>
<?php Profiler::log('page template: end', -1); ?>
</html>