<?php

$project_root = dirname(__DIR__);
chdir($project_root);

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/web-app-starter/?';
$_SERVER['PHP_SELF'] = '/web-app-starter/index.php';
$_SERVER['DOCUMENT_URI'] = '/web-app-starter/index.php';
$_GET = array();
$_POST = array();
$_REQUEST = array();
$_COOKIE = array();

require $project_root.'/config/settings.php';
require $project_root.'/lib/ulib.php';
require $project_root.'/lib/components.php';
require $project_root.'/lib/theme_helpers.php';