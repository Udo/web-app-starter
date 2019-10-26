<?php
  
class URL
{
  
  static $locator = '';
  static $route = array();
  static $error = '';
  static $title = 'TITLE';
  static $page_type = 'html';
  
  # extracts the locator string from parameters or the URI 
  static function GetLocatorString()
  {
    $loc = '/';
    $uri = first($_SERVER['REQUEST_URI']);
  
    if($uri != '')
    {
      $qstr = str_replace('?', '&', $uri);
      if(substr($qstr, 0, 1) == '&') $qstr = substr($qstr, 1);
      $qseg = explode('&', $qstr);
      if(sizeof($qseg) > 0)
      {
        if(stripos($qseg[0], '=') === false)
        {
          $loc = $qseg[0];
          array_shift($qseg);
        }
        parse_str(implode('&', $qseg), $urlParams);
        foreach($urlParams as $k => $v)
          $_REQUEST[$k] = $v;
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
    $route = array();
    if(!$lc)
      $lc = URL::GetLocatorString();
    $seg = array();
    foreach(explode('/', $lc) as $s) 
      if(substr($s, 0, 1) != '.' && $s != '') # strip unnecessary prefixes
        $seg[] = $s;
    $route['page'] = 'index';
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
    if(cfg('pretty_urls'))
    {
      return('/'.$path.($params !== false ? http_build_query($params) : ''));
    }
    else
    {
      return('.?'.$path.($params !== false ? '?'.http_build_query($params) : ''));
    }
  }

  # redirect to URL and quit
  static function Redirect($url = '/', $params = array())
  {
    header('location: '.$url.(sizeof($params) > 0 ? '?'.http_build_query($params) : ''));
    die();
  }
  
  
}
