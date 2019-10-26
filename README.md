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

## License

Copyright 2018 udo@openfu.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
