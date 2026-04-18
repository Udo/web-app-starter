<?php

	include('config/settings.php');
	include('lib/ulib.php');
	include('lib/components.php');
	include('lib/theme_helpers.php');

	URL::MakeRoute();

	Profiler::log('main content: start', 1);

	ob_start();
	$route_match = URL::ResolveViewFile('views');
	if($route_match)
	{
		if(isset($route_match['param']))
			URL::$route['param'] = $route_match['param'];
		require($route_match['file']);
	}
	else
	{
		URL::NotFound('The requested page does not exist.');
		URL::$route['page-title'] = '404 Not Found';
		echo '<section class="card">';
		echo '<h1>404 Not Found</h1>';
		echo '<p>'.safe(URL::$error).'</p>';
		echo '</section>';
	}
	URL::$fragments['main'] = ob_get_clean();

	Profiler::log('main content: end', -1);

	$page_template = cfg('theme/path').'/page.'.URL::$page_type.'.php';
	if(!file_exists($page_template))
		$page_template = 'themes/common/page.'.URL::$page_type.'.php';
	if(!file_exists($page_template))
		die('fatal error: page template not found ('.$page_template.')');
	require($page_template);

	Log::audit('page:'.URL::$route['page'], URL::$route['l-path']);
