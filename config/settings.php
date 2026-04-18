<?php 
	
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	ini_set('display_errors', 'on');
	
	// Handle JSON POST requests
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
		if (strpos($contentType, 'application/json') !== false) {
			$rawInput = file_get_contents('php://input');
			if ($rawInput) {
				$jsonData = json_decode($rawInput, true);
				if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
					$_POST = array_merge($_POST, $jsonData);
					$_REQUEST = array_merge($_REQUEST, $jsonData);
				}
			}
		}
	}
	
	define('DATE_FORMAT', 'd.m.Y');
	define('DATETIME_FORMAT', 'd.m.Y H:i');
	define('DATE_FORMAT_ZULU', 'Ymd\THis\Z');
	define('ONE_MINUTE', 60);
	define('ONE_HOUR', 60*60);
	define('ONE_DAY', 60*60*24);
	define('ONE_MONTH', 60*60*24*30);
	define('ONE_YEAR', 60*60*24*30*365);
	
	$GLOBALS['config'] = array(
	
		'url' => array(
			'pretty' => false,
			'root' => '',
			'content-type' => 'html',
		),
		'theme' => array(
			'key' => 'portal-dark',
			'path' => 'themes/portal-dark/',	
			'mode' => 'dark',
			'options' => array(
				'light' => array('label' => 'Starter Light', 'path' => 'themes/light/', 'mode' => 'light'),
				'dark' => array('label' => 'Starter Dark', 'path' => 'themes/dark/', 'mode' => 'dark'),
				'portal-light' => array('label' => 'AI Portal Light', 'path' => 'themes/portal-light/', 'mode' => 'light'),
				'portal-dark' => array('label' => 'AI Portal Dark', 'path' => 'themes/portal-dark/', 'mode' => 'dark'),
				'localfirst' => array('label' => 'Local First', 'path' => 'themes/localfirst/', 'mode' => 'dark'),
			),
		),
		'site' => array(
			'name' => 'Web App Starter',
			'default_page_title' => 'Home',
			'include_paths' => ['views/', 'components/', ''],
			'autostart_session' => true,
			'timezone' => 'UTC',
		),
		'filebase' => [
			'path' => '/tmp/',
		],
		'users' => array(
			'enable_signup' => true,	
		),
		'menu' => array(
			'' => array('title' => 'Home', 'hidden' => true),	
			'page1' => array('title' => 'Components',),
			'features' => array('title' => 'Features',),
			'page2' => array('title' => 'Ajaxy',),
			'gauges' => array('title' => 'Gauges',),
			'dashboard' => array('title' => 'Dashboard',),
			'workspace' => array('title' => 'Workspace',),
			'themes' => array('title' => 'Themes',),
			'auth/demo' => array('title' => 'Auth',),
		),
	
	);

	$theme_options = $GLOBALS['config']['theme']['options'] ?? array();
	$theme_default = $GLOBALS['config']['theme']['key'] ?? 'dark';
	$requested_theme = $_GET['theme'] ?? ($_COOKIE['starter_theme'] ?? $theme_default);
	if(!isset($theme_options[$requested_theme]))
	{
		$requested_theme = $theme_default;
	}
	if(isset($theme_options[$requested_theme]))
	{
		$GLOBALS['config']['theme']['key'] = $requested_theme;
		$GLOBALS['config']['theme']['path'] = $theme_options[$requested_theme]['path'];
		$GLOBALS['config']['theme']['mode'] = $theme_options[$requested_theme]['mode'] ?? 'light';
		$GLOBALS['config']['theme']['label'] = $theme_options[$requested_theme]['label'] ?? $requested_theme;
	}
	if(isset($_GET['theme']) && $_GET['theme'] === $requested_theme)
	{
		setcookie('starter_theme', $requested_theme, time() + (86400 * 180), '/');
		$_COOKIE['starter_theme'] = $requested_theme;
	}
	
	if(!$GLOBALS['config']['url']['pretty'] && !$GLOBALS['config']['url']['root'])
	{
		$sroot = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['DOCUMENT_URI'];
		if(substr($sroot, -9) == 'index.php')
			$GLOBALS['config']['url']['root'] = substr($sroot, 0, -strlen('index.php'));
	}

	date_default_timezone_set($GLOBALS['config']['site']['timezone'] ?: 'UTC');

	if($GLOBALS['config']['site']['autostart_session'])
	{
		session_start();
	}