<?php

require __DIR__.'/bootstrap.php';

function test_fail($message)
{
	fwrite(STDERR, "FAIL: {$message}\n");
	exit(1);
}

function test_assert($condition, $message)
{
	if(!$condition)
		test_fail($message);
	echo "ok - {$message}\n";
}

function reset_request($request_uri)
{
	$_SERVER['REQUEST_URI'] = $request_uri;
	$_GET = array();
	$_POST = array();
	$_REQUEST = array();
	URL::$route = array();
	URL::$locator = '';
	URL::MakeRoute();
}

reset_request('/web-app-starter/?themes&theme=portal-dark');
$themes_route = URL::ResolveViewFile('views');
test_assert($themes_route !== false && $themes_route['file'] === 'views/themes.php', 'theme gallery route resolves to the expected view');

reset_request('/web-app-starter/?workspace/projects');
$workspace_route = URL::ResolveViewFile('views');
test_assert($workspace_route !== false && $workspace_route['file'] === 'views/workspace/index.php', 'nested workspace route resolves to parent index');
test_assert(($workspace_route['param'] ?? null) === 'projects', 'nested workspace route exposes the trailing path segment as param');

test_assert(URL::Link('', ['theme' => 'portal-dark']) === '/web-app-starter/?theme=portal-dark', 'root theme link omits the empty route segment');
test_assert(URL::Link('themes', ['theme' => 'portal-dark']) === '/web-app-starter/?themes&theme=portal-dark', 'named route links keep the route segment and query parameters');

test_assert(component_exists('components/example/theme-switcher'), 'component existence works for explicit component paths');
test_assert(component_exists('example/theme-switcher'), 'component existence works for shorthand component paths');

ob_start();
component('example/theme-switcher');
$theme_switcher_html = ob_get_clean();
test_assert(str_contains($theme_switcher_html, 'theme-switcher'), 'component rendering works through the shorthand component API');

$portal_dark = cfg('theme/options/portal-dark');
test_assert(!empty($portal_dark['description']) && !empty($portal_dark['footer_text']), 'theme metadata is centralized in config');

echo "All smoke tests passed.\n";