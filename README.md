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

