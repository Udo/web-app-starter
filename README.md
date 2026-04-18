# Web App Starter

Simple web app starter package, expects to run in a domain's root directory but will run everywhere as long as cfg('url/pretty') is set to false.

Don't be afraid of server-side rendering! But we have JS options too.

## Nginx Configuration

### Pretty URLs Support (Optional)
For pretty URLs (`cfg('url/pretty') = true`), add this configuration to enable clean URLs like `/users/profile` instead of `/?users/profile`:

```nginx
location / {
	try_files $uri $uri/ /index.php?$args;
}

# the rest of the owl
location ~ ^/(config|lib|private|\.git)/ {
	deny all;
	return 404;
}
location ~ \.php$ {
	fastcgi_pass unix:/var/run/php/php-fpm.sock; 
	fastcgi_index index.php;
	fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	include fastcgi_params;
}
```

**Note**: Without pretty URL configuration, set `cfg('url/pretty') = false` in your config/settings.php and URLs will use query string format: `/?page&param=value`. You can generate URLs with `URL::Link()` which will automatically use the appropriate format if you want to keep your options open (as to whether to use pretty URLs or not).

## Routing

`URL::MakeRoute()` creates `URL::$route` data. For example, a request to `http://example.com/some/url/path` 
would look like this:

```
Array
(
	[page] => index
	[l-path] => some/url/path
)
``` 

The `page` key refers to the view should be rendered. By default, this is just `'index'`, leading to 
`'views/index.php'` getting invoked. If the URL's path element starts with a colon, the entire path
is used as the `page` value.

The `l-path` key refers to the actual URL path as given by the browser. For example, the `'views/index.php'`
view uses this to invoke the correct sub-view.

The starter also supports two additional patterns backported from downstream apps:

- directory index routing: `/workspace` can resolve to `views/workspace/index.php`
- parent-path fallback: `/workspace/activity` can resolve to `views/workspace/index.php` with `URL::$route['param'] === 'activity'`

## Components

Components are reusable PHP-backed view fragments. A component file returns an array with one or more render functions plus optional metadata.

The default starter convention is to keep reusable components in `components/`. The loader supports explicit paths like `components/example/theme-switcher` and shorthand names like `example/theme-switcher`, which resolve under `components/`.

```php
<?php return [

	'render' => function($prop) {
			return '<div class="my-component">' . $prop['content'] . '</div>';
	},
	
	'about' => 'Description of what this component does',

];
```

### Using Components

```php
<?= component('components/example/theme-switcher') ?>
```

**Using a Specific Render Method Explicitly:**
```php
<?= component_call('components/workspace/panel', 'render', ['title' => 'Status']) ?>
```

The older `component-name:method` shorthand is still supported for backwards compatibility, but `component_call()` or an explicit `render_call` prop is the preferred API because it is clearer and easier to grep.

## Dashboard Primitives

The starter now includes a small set of dashboard-focused building blocks backported from a more complex production frontend:

- `js/u-format.js` for human-readable byte, count, and duration formatting plus unit-aware parsing
- `js/u-sortable-table.js` for lightweight sortable HTML tables with persisted sort state
- `js/u-timeseries-chart.js` for multi-series canvas charts without pulling in a charting framework
- `components/data/summary-metrics.php`, `components/data/sortable-table.php`, and `components/data/timeseries-chart.php`
- `views/dashboard.php` as a working end-to-end example

## Workspace Primitives

The starter also includes a second backport slice from the `uh-ai` portal app:

- `components/workspace/*` for app shells, sidebars, panel headers, status pills, empty states, and compact utility buttons
- `themes/common/css/workspace.css` for the shared workspace shell styling
- `js/u-workspace-shell.js` for simple responsive sidebar toggling
- `views/workspace/index.php` as a nested-route demo for shell-style products

## Theme Families

The starter now includes named theme families derived from the `uh-ai` portal app and the `uh-llm2` admin shell:

- `portal-light` for the B612-based corporate portal look
- `portal-dark` for the glassy dark portal shell and the current default starter theme
- `localfirst` for the cyan/orange llm2-style admin shell

Theme choice is resolved server-side from `config/settings.php`, with a validated theme key stored in the `starter_theme` cookie. Theme metadata such as descriptions, footer text, and browser theme colors now live in the same config entry as the theme path, so the gallery, page shell, and docs all read from the same source of truth.

To compare themes side by side, use `/?themes`. The gallery embeds a shared `/?theme-preview` route under every available theme so you can evaluate shell chrome, content density, and form styling with the same fixture.

The page shell is now split between theme CSS/assets and shared helper functions in `lib/theme_helpers.php`, which keeps the four top-nav themes aligned while still allowing `localfirst` to provide its own admin-shell layout.

The homepage demo form now includes proper `id` and `name` attributes so the starter does not emit the earlier label/field accessibility warnings on the landing page.

## Gauge Primitives

The gauges demo now contains two gauge families from `uh-llm2`:

- `components/gauges/progressbar.php` and `components/gauges/needlegauge.php` for the original bar and needle gauges
- `components/gauges/arcgauge.php` for the llm2-style SVG KPI arc gauges with optional watermark tracking

Abstract gauge classes live in `themes/common/css/gauges.css`, so the components stay theme-aware through CSS variables instead of shipping a competing visual system.

## Testing

The starter now includes a no-dependency smoke suite under `tests/`.

Run it with:

```bash
php tests/smoke.php
```

It covers route resolution, link generation, component lookup, shorthand component rendering, and centralized theme metadata.

### Inline Components

You can also declare components directly in code:

```php
component_declare('my-button', [
	'render' => function($prop) { 
		return "<button class='{$prop['class']}'>{$prop['text']}</button>"; 
	}
]);
```

## Logging

This package includes a very basic Log class.

```php
Log::debug($module, $text) /* writes to log/debug.[Y]-[m].log */
Log::text($module, $text)   /* writes to log/log.[Y]-[m].log */
Log::audit($module, $text) /* writes to system journal */
```

`$module` should be a short string identifying the module or context of the log line.

`$text` text to be logged.

## Profiler

A basic profiling fixture.

```php
Profiler::log($text, $indent_level = 0) 
```

`$text` name/text describing the checkpoint to be profiled.

Profiler logs are not committed to disk. Log data for the current request is stored in
`Profiler::$log`.

## Batteries included
```
- lib/ulib.php - convenience functions
- lib/profiler.class.php - profiling
- lib/log.class.php - logging
- lib/url.class.php - URL routing
- lib/components.php - component rendering
- lib/odt.class.php - ODT document generator
- lib/db.class.php - database access

- js/morphdom.js - DOM diffing
- js/uquery.js - DOM manipulation (my attempt at keeping the good parts of jQuery)
- js/site.js - site-wide JS, as a starting point
- js/macrobars.js - JS templating

- js/ag-grid - ag-Grid
```
## License (MIT Open Source)

Copyright 2018-2025 udo@openfu.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
