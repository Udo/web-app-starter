<?php

	include('config/settings.php');
	include('lib/ulib.php');
	include('lib/components.php');

	URL::MakeRoute();

	Profiler::log('ready');

	ob_start();
	print(component('views/'.first(URL::$route['l-path'], 'index'), array($prop['id'] => $_REQUEST['attr']['id'])));
	URL::$fragments['main'] = ob_get_clean();

	$page_template = cfg('theme/path').'/page.'.URL::$page_type.'.php';
	if(!file_exists($page_template))
		$page_template = 'themes/common/page.'.URL::$page_type.'.php';
	if(!file_exists($page_template))
		die('fatal error: page template not found ('.$page_template.')');
	require($page_template);

	Log::audit('page:'.URL::$route['page'], URL::$route['l-path']);
