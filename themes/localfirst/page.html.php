<?php
	Profiler::log('page template: start', 1);
	$embed_mode = !empty($_GET['embed']);
	$currentPath = URL::$route['l-path'] ?? '';
?><!doctype html>
<html class="<?= asafe(theme_html_class()) ?>" lang="en" data-theme-key="<?= asafe((string)cfg('theme/key')) ?>">
<head>
	<?php theme_render_head(); ?>
</head>
<body class="admin-page localfirst-theme dark-theme<?= $embed_mode ? ' embed-mode' : '' ?>">
	<?php theme_render_global_controls($embed_mode, array('cookie_consent' => false)); ?>
	<div class="admin-shell">
		<nav class="admin-nav">
			<div class="admin-nav-header">
				<a class="admin-nav-title" href="<?= URL::Link('') ?>">
					<img class="admin-nav-logo-img" src="<?= url_root().ltrim((string)cfg('theme/path'), '/') ?>img/local_first_logo.png" alt="<?= safe(cfg('site/name')) ?>" />
				</a>
			</div>
			<?php foreach((array)cfg('menu') as $menu_key => $menu_item) if(empty($menu_item['hidden'])) { ?>
				<a class="admin-nav-item<?= ($menu_key !== '' && ($currentPath === $menu_key || strpos($currentPath, $menu_key.'/') === 0)) ? ' active' : '' ?>" href="<?= theme_menu_href($menu_key, $menu_item) ?>"><?= safe($menu_item['title']) ?></a>
			<?php } ?>
		</nav>
		<div class="admin-main">
			<?php if(!$embed_mode): ?><div class="admin-toolbar">
				<?php theme_render_account_links(array(
					'wrapper_class' => 'admin-account-card',
					'links_wrapper_class' => 'admin-account-links',
					'name_class' => 'admin-account-name',
				)); ?>
			</div><?php endif; ?>
			<div id="content" class="admin-content">
				<?= URL::$fragments['main'] ?>
			</div>
		</div>
	</div>
</body>
<?php Profiler::log('page template: end', -1); ?>
</html>