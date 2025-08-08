# url.class.php

A PHP URL routing and handling class for web applications with support for pretty URLs and request parsing.

## Static Properties

```php
URL::$locator                       // Current URL path
URL::$route                         // Parsed route information
URL::$error                         // Error message for 404s
URL::$title                         // Page title
URL::$page_type                     // Response type (default: 'html')
URL::$fragments                     // URL fragments array
URL::$tried                         // Attempted routes (debugging)
```

## Request Parsing

```php
URL::ParseRequestURI()              // Extract locator from REQUEST_URI
```

Parses URLs and populates `$_REQUEST` with query parameters. Handles both pretty URLs and query string format.

## Route Generation

```php
URL::MakeRoute($locator)            // Generate route from URL path
```

Creates route array with:
- `l-path` - Clean URL path segments
- `page` - Target page/view (defaults to 'index')
- URL config values

### Special Route Handling
- Paths starting with `:` use the entire path as page name
- Strips dots and empty segments for security
- Removes root path prefix

## URL Generation

```php
URL::Link($path, $params)           // Generate application URL
```

Creates URLs based on `cfg('url/pretty')` setting:
- **Pretty URLs**: `/path?param=value`
- **Query URLs**: `/?path&param=value`

## Navigation

```php
URL::Redirect($url, $params)        // Redirect and exit
URL::NotFound($message)             // Send 404 response
```

## Examples

### Basic Routing
```php
// Parse current request
$locator = URL::ParseRequestURI();   // Returns 'users/profile'
$route = URL::MakeRoute();           // Creates route array

// Access route data
echo URL::$route['l-path'];          // 'users/profile'
echo URL::$route['page'];            // 'index' (default)
```

### URL Generation
```php
// Generate links
$userLink = URL::Link('users/123');                    // '/users/123'
$searchLink = URL::Link('search', ['q' => 'term']);    // '/search?q=term'

// Redirect examples
URL::Redirect('login');                                // Redirect to login
URL::Redirect('dashboard', ['tab' => 'settings']);     // With parameters
```

### Special Page Routes
```php
// URL: /special/admin/users
// If URL starts with ':special', entire path becomes page name
URL::MakeRoute(':special/admin/users');
// Results in: page = 'special/admin/users'
```

### Error Handling
```php
// Set 404 status
URL::NotFound('Page not found');
echo URL::$error;                   // 'Page not found'

// Check attempted routes
var_dump(URL::$tried);              // Array of attempted route resolutions
```

## Configuration Integration

Uses configuration values:
- `cfg('url/root')` - Application root path
- `cfg('url/pretty')` - Enable/disable pretty URLs
- `$GLOBALS['config']['url']` - URL configuration array

## URL Parsing Logic

1. **Extract Path**: Removes query string from REQUEST_URI
2. **Parse Parameters**: Converts query string to $_REQUEST entries
3. **Clean Segments**: Removes dots, empty segments, security prefixes
4. **Route Resolution**: Determines target page and path
5. **Special Handling**: Processes colon-prefixed paths

## Security Features

- **Dot Prevention**: Strips segments starting with '.'
- **Path Sanitization**: Removes empty and problematic segments
- **Root Stripping**: Removes application root from paths

## Pretty URL Support

Supports both URL formats:
```php
// Pretty URLs (url/pretty = true)
/users/profile/edit
/search?q=term

// Query URLs (url/pretty = false)
/?users/profile/edit
/?search&q=term
```


