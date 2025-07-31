<?php

	include('config/settings.php');
	include('lib/ulib.php');
	include('lib/components.php');

	URL::MakeRoute();

	Profiler::log('main content: start', 1);

	$content_file = 'views/'.first(URL::$route['l-path'], 'index');
	ob_start();
	if(file_exists($content_file.'.php'))
	{
		require($content_file.'.php');
	}
	else
	{
		header('HTTP/1.0 404 Not Found');
		echo '<h1>404 Not Found</h1>';
		echo '<p>The requested page does not exist.</p>';
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
