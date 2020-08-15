<?php
	
class URL
{

	static $locator = '';
	static $route = array();
	static $error = '';
	static $title = 'TITLE';
	static $page_type = 'html';
	
	# extracts the locator string from parameters or the URI 
	static function ParseRequestURI()
	{
		$uri = first($_SERVER['REQUEST_URI']);
		$loc = nibble('?', $uri);
		$seg_count = 0;
	
		if($uri != '')
		{
			while($uri != '')
			{
				$seg_count += 1;
				$seg = nibble('&', $uri);
				if(stristr($seg, '=') === false && $seg_count == 1)
				{
					$loc .= $seg;
				}
				else
				{
					$k = nibble('=', $seg);
					$_REQUEST[$k] = $seg;
				}
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
		if(substr($seg[0], 0, 1) == ':')
		{
			$route['page'] = substr($route['l-path'], 1);
		}
		if(sizeof(self::$route) == 0) self::$route = $route;
		return($route);
	}

	static function Link($path, $params = false)
	{
		if(cfg('url/pretty'))
		{
			return($GLOBALS['config']['url']['root'].$path.($params !== false ? http_build_query($params) : ''));
		}
		else
		{
			return($GLOBALS['config']['url']['root'].'?'.$path.($params !== false ? '&'.http_build_query($params) : ''));
		}
	}

	# redirect to URL and quit
	static function Redirect($url = '', $params = array())
	{
		header('location: '.self::Link($url, $params));
		die();
	}
	
	
}
