# Web App Starter - AI Assistant Instructions

## Project Architecture
**Custom PHP framework** with server-side rendering focus, component-based UI, and clean URL routing.

### Core Flow
1. `index.php` → `URL::MakeRoute()` → `views/{page}.php` → theme template
2. All requests flow through `index.php` (single entry point)
3. Views render into `URL::$fragments['main']`, then wrapped by theme

### Key Directories
- `lib/` - Core classes (URL, DB, Log, Profiler, components)
- `views/` - Page templates (maps to URL routes)
- `components/` - Reusable UI components
- `config/settings.php` - Main configuration
- `themes/` - Page layout templates
- `js/` - Client-side code (uquery.js, morphdom.js, etc.)

## Core Classes & Functions

### Configuration
```php
cfg('key/path')           // Get config value
cfg('url/pretty')         // true=clean URLs, false=query strings
```

### URL Routing
```php
URL::MakeRoute()          // Parse URL into URL::$route
URL::Link($path, $params) // Generate URLs (respects pretty URL setting)
URL::$route['page']       // Target view (default: 'index')
URL::$route['l-path']     // Full URL path
URL::$fragments['main']   // Main content area
```

### Components
**Files return arrays with render functions:**
```php
<?php return [
    'render' => function($prop) { return '<div>...</div>'; },
    'about' => 'Component description'
];
```

**Usage:**
```php
component('path/to/component', ['prop' => 'value'])
component('path/to/component:method', $props)  // Specific render method
component_declare('name', $definition)        // Inline declaration
```

### Database
```php
DB::query($sql, $params)     // Execute query
DB::fetch($sql, $params)     // Single row
DB::fetchAll($sql, $params)  // Multiple rows
```

### Logging
```php
Log::debug($module, $text)   // log/debug.YYYY-mm.log
Log::text($module, $text)    // log/log.YYYY-mm.log  
Log::audit($module, $text)   // System journal
```

### Profiler
```php
Profiler::log($text, $indent_level)  // Runtime profiling
Profiler::$log                       // Access logged data
```

## Coding Conventions

### File Structure
- Views: `views/{pagename}.php`
- Components: `components/{category}/{name}.php`
- Classes: `lib/{name}.class.php`
- Utilities: `lib/{name}.php`

### JS and PHP Coding Style
- Use `<?=` for output, use escaping functions safe(), asafe(), and jsafe() as appropriate
- snake_case for functions/methods
- PascalCase for classes
- kebab-case for file/directory names
- Always include `<?php` opening tag
- Use uquery.js (documentation: uquery.md) for DOM manipulation

### Component Pattern
```php
<?php return [
    'render' => function($prop) {
        return "<div class='{$prop['class']}'>{$prop['content']}</div>";
    },
    'render:variant' => function($prop) { /* alternative render */ },
    'about' => 'Component description'
];
```

### URL Handling
- Use `URL::Link()` for all internal links
- Views auto-map to URLs (`views/users.php` → `/users`)

## Built-in Libraries
- **uquery.js** - jQuery-like DOM manipulation
- **morphdom.js** - DOM diffing
- **macrobars.js** - JavaScript templating

## Common Patterns

### New Page
1. Create `views/{pagename}.php`
2. Set URL::$page_type to 'json' or 'blank' if page is AJAX
3. Content (auto-wrapped by theme page variant controlled by URL::$page_type)

### New Component
1. Create `components/{category}/{name}.php`
2. Return array with `render` function
3. Use `component('components/{category}/{name}', $props)`

### Error Handling
- Logging: Use `Log::debug()` for development, `Log::audit()` for security
- Profiling: Use `Profiler::log()` for performance analysis

## Development Notes
- Server-side rendering preferred over SPA approach
- Component system encourages reusability
- Single entry point simplifies routing/middleware
