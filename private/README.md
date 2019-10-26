# Private Data Directory

Add this to your nginx config:

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
