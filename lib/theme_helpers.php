<?php

function theme_option($key, $default = null)
{
	$themes = cfg('theme/options');
	$current_theme_key = (string)cfg('theme/key');
	$current_theme = is_array($themes) && isset($themes[$current_theme_key]) ? $themes[$current_theme_key] : array();
	return array_key_exists($key, $current_theme) ? $current_theme[$key] : $default;
}

function theme_html_class($extra_class = '')
{
	$classes = array('no-js');
	if(cfg('theme/mode') === 'dark')
		$classes[] = 'dark-theme';
	if($extra_class !== '')
		$classes[] = $extra_class;
	return trim(implode(' ', $classes));
}

function theme_render_head($overrides = array())
{
	$theme_path = (string)cfg('theme/path');
	$theme_path_common = 'themes/common/';
	$description = first($overrides['description'] ?? false, theme_option('meta_description', ''), 'Web App Starter theme');
	$theme_color = first($overrides['theme_color'] ?? false, theme_option('theme_color', ''), '#0f172a');
	$apple_icon = url_root().ltrim($theme_path, '/').'icon.png';
	?>
	<meta charset="utf-8">
	<title><?= first(URL::$route['page-title'] ?? false, cfg('site/default_page_title')).' | '.cfg('site/name') ?></title>
	<meta name="description" content="<?= asafe((string)$description) ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="<?= asafe((string)$theme_color) ?>">
	<link rel="apple-touch-icon" href="<?= $apple_icon ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= $apple_icon ?>">
	<?php
	include_css($theme_path.'css/style.css');
	include_css($theme_path_common.'fontawesome/css/all.min.css');
	include_js('js/u-query.js');
	include_js('js/morphdom.js');
	include_js('js/site.js');
	foreach((array)($overrides['extra_css'] ?? array()) as $extra_css)
		include_css($extra_css);
	foreach((array)($overrides['extra_js'] ?? array()) as $extra_js)
		include_js($extra_js);
}

function theme_render_global_controls($embed_mode, $options = array())
{
	if($embed_mode)
		return;
	$show_theme_switcher = array_key_exists('theme_switcher', $options) ? (bool)$options['theme_switcher'] : true;
	$show_cookie_consent = array_key_exists('cookie_consent', $options) ? (bool)$options['cookie_consent'] : true;
	if($show_theme_switcher)
		echo component('components/example/theme-switcher');
	if($show_cookie_consent)
		echo component('components/basic/cookie-consent');
}

function theme_menu_href($menu_key, $menu_item)
{
	if(!empty($menu_item['external']))
		return '/'.ltrim((string)$menu_key, '/');
	return URL::Link((string)$menu_key);
}

function theme_get_user_context()
{
	require_once __DIR__.'/user.class.php';
	$signed_in = User::IsSignedIn();
	$profile = $signed_in ? (User::$current_profile ?? array()) : array();
	return array(
		'signed_in' => $signed_in,
		'profile' => $profile,
		'avatar' => first($profile['avatar_url'] ?? false, $profile['profile_image'] ?? false),
		'display_name' => first($profile['username'] ?? false, $profile['email'] ?? false, 'Account'),
	);
}

function theme_render_account_links($options = array())
{
	$context = theme_get_user_context();
	$wrapper_class = trim((string)first($options['wrapper_class'] ?? false, 'nav-account'));
	$name_class = trim((string)first($options['name_class'] ?? false, 'account-name'));
	$links_wrapper_class = trim((string)($options['links_wrapper_class'] ?? ''));
	$show_avatar = !empty($options['show_avatar']);
	$show_name = array_key_exists('show_name', $options) ? (bool)$options['show_name'] : true;
	?><div class="<?= asafe($wrapper_class) ?>"><?php
	if($context['signed_in'])
	{
		if($show_avatar && $context['avatar'])
		{
			?><img src="<?= safe($context['avatar']) ?>" alt="Profile" class="nav-avatar" onerror="this.style.display='none';"><?php
		}
		if($show_name)
		{
			?><span class="<?= asafe($name_class) ?>"><?= safe((string)$context['display_name']) ?></span><?php
		}
		if($links_wrapper_class !== '')
			?><div class="<?= asafe($links_wrapper_class) ?>"><?php
		?><a href="<?= URL::Link('account/profile') ?>">Profile</a>
		<a href="<?= URL::Link('account/logout') ?>">Logout</a><?php
		if($links_wrapper_class !== '')
			?></div><?php
	}
	else
	{
		if($links_wrapper_class !== '')
			?><div class="<?= asafe($links_wrapper_class) ?>"><?php
		?><a href="<?= URL::Link('account/login') ?>">Login</a><?php
		if(cfg('users/enable_signup'))
		{
			?><a href="<?= URL::Link('account/register') ?>">Register</a><?php
		}
		if($links_wrapper_class !== '')
			?></div><?php
	}
	?></div><?php
}

function theme_render_standard_nav($options = array())
{
	$nav_class = trim((string)($options['nav_class'] ?? ''));
	$account_wrapper_class = trim((string)first($options['account_wrapper_class'] ?? false, 'nav-account'));
	$show_avatar = array_key_exists('show_avatar', $options) ? (bool)$options['show_avatar'] : true;
	?><nav<?= $nav_class !== '' ? ' class="'.asafe($nav_class).'"' : '' ?>>
		<div class="nav-menu">
			<a href="<?= URL::Link('') ?>"><?= cfg('site/name') ?></a>
			<?php foreach((array)cfg('menu') as $menu_key => $menu_item) if(empty($menu_item['hidden'])) { ?>
				<a href="<?= theme_menu_href($menu_key, $menu_item) ?>"><?= safe($menu_item['title']) ?></a>
			<?php } ?>
		</div>
		<?php theme_render_account_links(array(
			'wrapper_class' => $account_wrapper_class,
			'name_class' => 'account-name',
			'show_avatar' => $show_avatar,
		)); ?>
	</nav><?php
}

function theme_render_footer($text = null, $options = array())
{
	if(!empty($options['embed_mode']))
		return;
	$footer_text = first($text, theme_option('footer_text', false), cfg('site/name'));
	$inner_class = trim((string)($options['inner_class'] ?? ''));
	?><footer><?php
	if($inner_class !== '')
	{
		?><div class="<?= asafe($inner_class) ?>"><p><?= safe((string)$footer_text) ?></p></div><?php
	}
	else
	{
		?><div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;"><p><?= safe((string)$footer_text) ?></p></div><?php
	}
	?></footer><?php
}