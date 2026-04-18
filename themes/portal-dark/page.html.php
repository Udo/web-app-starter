<?php
	Profiler::log('page template: start', 1);
	$embed_mode = !empty($_GET['embed']);
?><!doctype html>
<html class="<?= asafe(theme_html_class()) ?>" lang="en" data-theme-key="<?= asafe((string)cfg('theme/key')) ?>">
<head>
	<?php theme_render_head(); ?>
</head>
<body class="portal-theme portal-dark-theme dark-theme<?= $embed_mode ? ' embed-mode' : '' ?>">
	<?php theme_render_global_controls($embed_mode); ?>
	<?php theme_render_standard_nav(array('account_wrapper_class' => 'nav-account nav-menu')); ?>
	<div id="content"<?= $embed_mode ? ' class="embed-content"' : '' ?>>
		<?= URL::$fragments['main'] ?>
	</div>
	<?php theme_render_footer(null, array('embed_mode' => $embed_mode)); ?>
</body>
<?php Profiler::log('page template: end', -1); ?>
</html>