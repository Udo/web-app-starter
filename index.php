<?php

	include('lib/ulib.php');
	include('config/settings.php');
	include('lib/render.php');
	
	URL::MakeRoute();
	User::init();

	ob_start();	
	print(component(first(URL::$route['l-path'], 'index'), array($prop['id'] => $_REQUEST['attr']['id'])));
	$content = ob_get_clean();
		
	include(cfg('theme/path').'/page.'.URL::$route['content-type'].'.php');
	
	Log::audit('page:'.URL::$route['page'], URL::$route['l-path']);
