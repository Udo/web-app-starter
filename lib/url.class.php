<?php
	
class URL
{

	static $locator = '';
	static $route = array();
	static $error = '';
	static $title = 'TITLE';
	static $page_type = 'html';
	static $fragments = [];
	
	# extracts the locator string from parameters or the URI 
	static function ParseRequestURI()
	{
		$uri = (string)first($_SERVER['REQUEST_URI']);
		$parsed_uri = parse_url($uri);
		$loc = (string)($parsed_uri['path'] ?? '');
		$query_string = (string)($parsed_uri['query'] ?? '');
		if($query_string !== '')
		{
			$route_found = false;
			$query_parts = explode('&', $query_string);
			$named_parts = array();
			foreach($query_parts as $query_part)
			{
				if($query_part === '')
					continue;
				if(!$route_found && str_contains($query_part, '=') === false)
				{
					$loc = rawurldecode($query_part);
					$route_found = true;
					continue;
				}
				$named_parts[] = $query_part;
			}
			if(sizeof($named_parts) > 0)
			{
				$params = array();
				parse_str(implode('&', $named_parts), $params);
				$_REQUEST = array_merge($_REQUEST, $params);
			}
		}
		
		self::$locator = $loc;
		return($loc);
	}
	
	static function NotFound($message = 'resource not found')
	{
		header("HTTP/1.0 404 Not Found");
		self::$error = $message;
	}
	
	static $tried = array();
	
	# determines which view to show given a locator string 
	static function MakeRoute($lc = false)
	{
		$route = $GLOBALS['config']['url'];
		if(!$lc)
			$lc = URL::ParseRequestURI();
		if(str_starts_with($lc, $GLOBALS['config']['url']['root']))
			$lc = substr($lc, strlen($GLOBALS['config']['url']['root']));
		$seg = array();
		foreach(explode('/', $lc) as $s) 
			if(substr($s, 0, 1) != '.' && $s != '') # strip unnecessary prefixes
				$seg[] = $s;
		$route['l-path'] = implode('/', $seg);
		$route['page'] = first($seg[0] ?? false, 'index');
		if(isset($seg[0]) && $seg[0] !== '' && substr($seg[0], 0, 1) == ':')
		{
			$route['page'] = substr($route['l-path'], 1);
		}
		if(sizeof(self::$route) == 0) self::$route = $route;
		return($route);
	}

	static function ResolveViewFile($base_dir = 'views')
	{
		$lpath = first(self::$route['l-path'], 'index');
		$base_dir = rtrim($base_dir, '/');
		$content_file = $base_dir.'/'.$lpath;
		if(file_exists($content_file.'.php'))
			return array('file' => $content_file.'.php');
		$dir_index_file = $content_file.'/index.php';
		if(file_exists($dir_index_file))
			return array('file' => $dir_index_file);
		$lpath_parts = array_values(array_filter(explode('/', $lpath), function($segment) {
			return $segment !== '';
		}));
		if(sizeof($lpath_parts) > 1)
		{
			$last_seg = array_pop($lpath_parts);
			$parent_file = $base_dir.'/'.implode('/', $lpath_parts).'/index.php';
			if(file_exists($parent_file))
				return array('file' => $parent_file, 'param' => $last_seg);
		}
		return false;
	}

	static function Link($path, $params = false)
	{
		$path = ltrim((string)$path, '?');
		$query = $params !== false ? http_build_query($params) : '';
		if(cfg('url/pretty'))
		{
			return($GLOBALS['config']['url']['root'].$path.($query !== '' ? '?'.$query : ''));
		}
		else
		{
			if($path === '')
				return($GLOBALS['config']['url']['root'].($query !== '' ? '?'.$query : ''));
			return($GLOBALS['config']['url']['root'].'?'.$path.($query !== '' ? '&'.$query : ''));
		}
	}

	# redirect to URL and quit
	static function Redirect($url = '', $params = array())
	{
		header('location: '.self::Link($url, $params));
		die();
	}
	
	
}
