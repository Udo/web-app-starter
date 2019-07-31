<?php
  
class URL
{
  
  static $locator = '';
  static $route = array();
  static $error = '';
  static $title = 'TITLE';
  
  # extracts the locator string from parameters or the URI 
  static function GetLocatorString()
  {
    $loc = '/';
    $uri = first($_SERVER['REQUEST_URI']);
    if(stripos($uri, $GLOBALS['WEB.BASEDIR']) === 0)
      $uri = substr($uri, strlen($GLOBALS['WEB.BASEDIR']));
  
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
  
  static function TryRouteView($view)
  {
    $vf = $view .'.php';
    if(file_exists($vf))
    {
      self::$tried[] = $view.':+';
      return($vf);
    }
    self::$tried[] = $view.':-';
  }
  
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
    $route['l-path'] = implode('/', $seg);
    $route['logged_in'] = @$_SESSION['userstatus'] == 'loggedin';
    $lc = implode('/', $seg);
    if(substr($lc, -1) == '/') $lc = substr($lc, 0, -1);
    $route['locator'] = $lc;
    $route['view'] = false;
    if(!$route['view']) $route['view'] = self::TryRouteView('views/'.first($lc, 'index'));
    if(!$route['view']) $route['view'] = self::TryRouteView('views/'.first($lc, 'home').'/index');
    if(!$route['view'])
    {
      $still_matching = true;
      $path = array();
      foreach($seg as $sp) if($still_matching)
      {
        $pathc = implode('/', $path);
        if(file_exists('packages/'.$pathc.'/'.$sp) || file_exists('packages/'.$pathc.'/'.$sp.'.php'))
        {
          $path[] = $sp;
        }
        else if(file_exists('packages/'.$pathc.'/any'))
        {
          $route['any_match'][] = $sp;
          $path[] = 'any';
        }
      }
    }
    if($GLOBALS['config']['app']['onroute'])
      $GLOBALS['config']['app']['onroute']($route);
    $categoryCodeFN = 'views/'.$seg[0].'/_common.php';
    if(file_exists($categoryCodeFN))
    {
      $route['viewcommon'] = $categoryCodeFN;
    }
    $route['is_webhook'] = substr($route['locator'], 0, 8) == 'webhook/';
    $GLOBALS['pagetype'] = 'html';
    if($_POST['inline'] == 'Y')
    {
      $GLOBALS['pagetype'] = 'html-inline';
      unset($_REQUEST['inline']);
      $route['inline'] = true;      
    }
    #if(!$route['logged_in'] && !$route['is_webhook'])
    #  $route['view'] = 'views/screens/login.php';
    $route['nav_highlight'] = $route['l-path'];
    if($_REQUEST['debug']) $route['tried'] = self::$tried;
    if(sizeof(self::$route) == 0)
      self::$route = $route;
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
