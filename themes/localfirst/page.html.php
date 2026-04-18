<?php
	Profiler::log('page template: start', 1);
	$url_root = cfg('url/root');
	if(!$url_root) $url_root = '/';
	if(substr($url_root, -1) !== '/') $url_root .= '/';
	$embed_mode = !empty($_GET['embed']);
	$theme_path = cfg('theme/path');
	$theme_path_common = 'themes/common/';
	require_once __DIR__ . '/../../lib/user.class.php';
	$currentPath = URL::$route['l-path'] ?? '';
?><!doctype html>
<html class="no-js dark-theme" lang="en" data-theme-key="<?= asafe((string)cfg('theme/key')) ?>">
<head>
	<meta charset="utf-8">
	<title><?= first(URL::$route['page-title'], cfg('site/default_page_title')).' | '.cfg('site/name') ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#020913">
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
<body class="admin-page localfirst-theme dark-theme<?= $embed_mode ? ' embed-mode' : '' ?>">
	<?php if(!$embed_mode): ?><?= component('components/example/theme-switcher') ?><?php endif; ?>
	<div class="admin-shell">
		<nav class="admin-nav">
			<div class="admin-nav-header">
				<a class="admin-nav-title" href="<?= URL::link('') ?>">
					<img class="admin-nav-logo-img" src="<?= $url_root.ltrim($theme_path, '/') ?>img/local_first_logo.png" alt="<?= safe(cfg('site/name')) ?>" />
				</a>
			</div>
			<?php foreach(cfg('menu') as $mk => $menu_item) if(!$menu_item['hidden']) { $href = !empty($menu_item['external']) ? '/'.ltrim($mk, '/') : URL::link($mk); ?>
				<a class="admin-nav-item<?= ($mk !== '' && ($currentPath === $mk || strpos($currentPath, $mk.'/') === 0)) ? ' active' : '' ?>" href="<?= $href ?>"><?= safe($menu_item['title']) ?></a>
			<?php } ?>
		</nav>
		<div class="admin-main">
			<?php if(!$embed_mode): ?><div class="admin-toolbar">
				<?php if (User::IsSignedIn()) { $__u = User::$current_profile; ?>
					<div class="admin-account-card">
						<span class="admin-account-name"><?= safe((string)first($__u['username'] ?? false, $__u['email'] ?? false, 'Account')) ?></span>
						<div class="admin-account-links">
							<a href="<?= URL::link('account/profile') ?>">Profile</a>
							<a href="<?= URL::link('account/logout') ?>">Logout</a>
						</div>
					</div>
				<?php } else { ?>
					<div class="admin-account-card">
						<div class="admin-account-links">
							<a href="<?= URL::link('account/login') ?>">Login</a>
							<?php if(cfg('users/enable_signup')): ?><a href="<?= URL::link('account/register') ?>">Register</a><?php endif; ?>
						</div>
					</div>
				<?php } ?>
			</div><?php endif; ?>
			<div id="content" class="admin-content">
				<?= URL::$fragments['main'] ?>
			</div>
		</div>
	</div>
</body>
<?php Profiler::log('page template: end', -1); ?>
</html>