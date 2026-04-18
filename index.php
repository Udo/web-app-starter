<?php

	include('config/settings.php');
	include('lib/ulib.php');
	include('lib/components.php');

	URL::MakeRoute();

	Profiler::log('main content: start', 1);

	$content_file = 'views/'.first(URL::$route['l-path'], 'index');
	$dir_index_file = $content_file.'/index';
	ob_start();
	if(file_exists($content_file.'.php'))
	{
		require($content_file.'.php');
	}
	else if(file_exists($dir_index_file.'.php'))
	{
		require($dir_index_file.'.php');
	}
	else
	{
		$lpath_parts = explode('/', URL::$route['l-path']);
		if(count($lpath_parts) > 1)
		{
			$last_seg = array_pop($lpath_parts);
			$parent_file = 'views/'.implode('/', $lpath_parts).'/index';
			if(file_exists($parent_file.'.php'))
			{
				URL::$route['param'] = $last_seg;
				require($parent_file.'.php');
			}
			else
			{
				header('HTTP/1.0 404 Not Found');
				echo '<h1>404 Not Found</h1>';
				echo '<p>The requested page does not exist.</p>';
			}
		}
		else
		{
			header('HTTP/1.0 404 Not Found');
			echo '<h1>404 Not Found</h1>';
			echo '<p>The requested page does not exist.</p>';
		}
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
