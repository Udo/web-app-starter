# ulib.php

A collection of PHP utility functions for common web development tasks.

## Autoloading & Setup

The library automatically registers a class autoloader and starts the profiler:

```php
// Auto-loads classes from lib/{classname}.class.php
require_once 'lib/ulib.php';
```

## Asset Management

```php
include_js('js/script.js')          // Outputs <script> tag with cache busting
include_css('css/style.css')        // Outputs <link> tag with cache busting
get_file_location($file)            // Find file in include paths
```

Assets are automatically versioned with `filemtime()` for cache busting.

## HTML/Security Functions

```php
safe($text)                         // HTML escape for content
asafe($text)                        // HTML escape for attributes (strips newlines)
jsafe($data)                        // JSON encode (alias for json_encode)
```

## Configuration

```php
cfg('database/host')                // Get config value with slash notation
cfg('url/root')                     // Access nested config arrays
```

Reads from `$GLOBALS['config']` with support for nested keys using `/` separator.

## Utility Functions

```php
first($a, $b, $c)                   // Return first non-empty value
alnum($text, '_', true)             // Keep only alphanumeric + spaces
write_to_file($file, $content)      // Append content to file
```

### first() Examples
```php
$name = first($user_input, $default_name, 'Anonymous');
$config = first($_GET['theme'], $_COOKIE['theme'], 'default');
```

## String Processing

### String Parsing
```php
nibble(':', $path, $found)          // Extract substring before delimiter
// Example: nibble(':', 'user:pass', $found) → 'user', $path becomes 'pass'
```

### Date/Time
```php
age_to_string($timestamp)           // Human-friendly time differences
// Examples: "just now", "5 min ago", "2 h ago", "Mon 14:30"
```

### Hash Generation
```php
make_hash()                         // Generate random hash (10 chars)
make_hash($input, 20)               // Hash specific input (20 chars)
```

### Base Conversion
```php
base_convert_any($num, $from, $to)  // Convert between any number bases
// Example: base_convert_any('FF', '0123456789ABCDEF', '0123456789') → '255'
```

## Advanced Features

- **Include Path Resolution**: Searches multiple paths for files
- **Automatic Permissions**: Sets 0777 on written files
- **Cache Busting**: Automatic versioning for JS/CSS
- **Reference Parameters**: Functions like `nibble()` modify input variables
- **Flexible Base Conversion**: Support for custom character sets

## Common Usage Patterns

```php
// Configuration-driven asset loading
include_css(cfg('theme/css_file'));

// Safe template output
echo '<div title="' . asafe($user_input) . '">' . safe($content) . '</div>';

// Fallback values
$title = first($_POST['title'], $default_title, 'Untitled');

// URL parsing
$protocol = nibble('://', $url);  // Extract 'http' from 'http://example.com'

// Time display
echo 'Posted ' . age_to_string($post_timestamp);
```

## Error Handling

Functions include built-in error handling:
- `get_file_location()` dies with error message if file not found
- `write_to_file()` uses error suppression for chmod
- Most functions return safe defaults for invalid input
