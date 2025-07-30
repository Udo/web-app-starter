<?php

	include('config/settings.php');
	include('lib/ulib.php');
	include('lib/components.php');

	URL::MakeRoute();
	User::init();

	Profiler::log('ready');

	ob_start();
	print(component('views/'.first(URL::$route['l-path'], 'index'), array($prop['id'] => $_REQUEST['attr']['id'])));
	URL::$fragments['main'] = ob_get_clean();

	include(cfg('theme/path').'/page.'.URL::$page_type.'.php');

	Log::audit('page:'.URL::$route['page'], URL::$route['l-path']);
