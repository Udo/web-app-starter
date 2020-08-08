# Web App Starter

Simple web app starter package, expects to run in a domain's root directory.

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

This uses the idea of immediate components to render HTML. For example, a given view may look like this:

```php
<? return(function($prop) { ?>

<!doctype html><html><head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
  </head>
  <body>  
    <?= component('nav/shortcut-bar') ?>
    <?= component('nav/menu', 'menu') ?>
    <?= component('nav/content', array('id' => 'content')) ?>
  </body>  
</html><? });
```

This would in turn render the components `'nav/shortcut-bar'`, `'nav/menu'`, and `'nav/content'`.
Components are located in `views/`. The function signature of a component() call looks like this:

```php
component($file_name, $id_override = false, $prop = array())
```

`$file_name` is the component's file name withint `views/`. Invoking the component() executes that file.
If the file returns a lambda function, that function gets called every time the component is invoked. If the
file returns a text, that text gets returned every time the component is invoked.

`$prop` is an optional parameter to communicate further parameters to the component, and this
corresponds to the `$prop` parameter being passed to a component's lambda function.

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
Profiler::log($text, $backtrace = false) 
```

`$text` name/text describing the checkpoint to be profiled.
`$backtrace` optional data attachment.

Profiler logs are not committed to disk. Log data for the current request is stored in
`Profiler::$log`.

## ulib Convenience Functions

### function first($p1, ...)

Takes any number of parameters and returns the first that is not null or empty.

### function write_to_file($filename, $content)

Appends `$content` to `$filename`.

### function map($list, $func)

Performs a map operation on `$list` using the `$func` predicate and returns a new array with the result.

### function reduce($list, $func)

Like `map()` but only adds results on entries where `$func` does not return null.

### function cfg($key)

Shortcut for accessing the configuration global `$GLOBALS['config']`. For example, a `$key` of `'db/host'` would 
translate to `$GLOBALS['config']['db']['host']`.

### function nibble($segdiv, &$cake, &$found = false)

Cuts off the part of `&$cake` before the first occurrence of `$segdiv`, and returns that part.

### function starts_with($s, $match) / function ends_with($s, $match)

Returns true if `$s` starts/ends with `$match`.

## License (MIT Open Source)

Copyright 2018 udo@openfu.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
