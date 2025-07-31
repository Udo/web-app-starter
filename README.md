# Web App Starter

Simple web app starter package, expects to run in a domain's root directory but will run everywhere as long as cfg('url/pretty') is set to false.

Add this to your nginx config to protect sensitive data directories:

```
			 
	location /private/ {
		deny all;
		return 404;
	}

	location /.git {
		deny all;
		return 404;
	}
	
```

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

## Components

Components for reusable UI elements. Components are PHP files that return an array with render functions and metadata.

### Component Structure

Components are located in the `components/` directory and follow this structure:

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
<?= component('components/basic/button', [
	'text' => 'Click me',
	'type' => 'primary'
]) ?>
```

**Using Specific Render Methods:**
```php
<?= component('components/layout/section:begin', ['class' => 'hero']) ?>
	<h1>Content here</h1>
<?= component('components/layout/section:end') ?>
```

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

## License (MIT Open Source)

Copyright 2018 udo@openfu.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
