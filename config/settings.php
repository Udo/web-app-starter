<?php 
  
  error_reporting(E_ALL ^ E_NOTICE);
  ini_set('display_errors', 'on');
  date_default_timezone_set('Europe/Berlin');
     
  session_start();
  $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
  
  define('DATE_FORMAT', 'd.m.Y');
  define('DATETIME_FORMAT', 'd.m.Y H:i');
  define('DATE_FORMAT_ZULU', 'Ymd\THis\Z');
  define('ONE_MINUTE', 60);
  define('ONE_HOUR', 60*60);
  define('ONE_DAY', 60*60*24);
  define('ONE_MONTH', 60*60*24*30);
  define('ONE_YEAR', 60*60*24*30*365);
  
  $GLOBALS['config'] = array(
  
	# fill me
  
  );
