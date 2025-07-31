# db.class.php

A PHP database abstraction layer for MySQL/MariaDB with caching, parameter binding, and profiling.

## Connection

```php
DB::connect()                       // Auto-connects using config
DB::isConnected()                   // Check connection status
```

Uses configuration from `cfg('db/host')`, `cfg('db/user')`, etc.

## Basic Queries

```php
DB::Query($sql, $params)            // Execute any SQL query
DB::Get($sql, $params)              // Get multiple rows as array
DB::GetRowWithQuery($sql, $params)  // Get single row
DB::GetCached($sql, $params)        // Cached version of Get()
```

## CRUD Operations

### Reading Data
```php
DB::GetRow($table, $keyvalue)       // Get row by primary key
DB::GetRow($table, $id, $keyname)   // Get row by specific key
DB::GetRowsMatch($table, $criteria) // Get rows matching criteria
```

### Writing Data
```php
DB::Insert($table, $data)           // Insert new row, returns ID
DB::Commit($table, $data)           // Insert or update (REPLACE)
DB::Update($table, $where, $data)   // Update existing rows
DB::RemoveRow($table, $keyvalue)    // Delete row by key
```

## Parameter Binding

### Positional Parameters
```php
DB::Query('SELECT * FROM users WHERE id = ? AND name = ?', [$id, $name]);
DB::Query('SELECT * FROM users WHERE age > & AND active = ?', [$age, $active]);
```

### Named Parameters
```php
DB::Query('SELECT * FROM users WHERE name = :name', ['name' => $username]);
DB::Query('SELECT * FROM users WHERE age > ::age', ['age' => 25]); // Number format
```

Parameter formats:
- `?` - String (escaped)
- `&` - Number (unescaped integer)
- `:name` - Named string parameter
- `::name` - Named number parameter

## Table Information

```php
DB::Keys($table)                    // Get primary key column names
DB::Info($table)                    // Get full column information
```

## Utility Methods

```php
DB::Safe($string)                   // Escape string for SQL
DB::MakeNamesList($array)           // Create column list for INSERT
DB::MakeValuesList($array)          // Create values list for INSERT  
DB::MakeSetList($array, $separator) // Create SET clause for UPDATE
```

## Advanced Features

### Table Prefix Support
```php
DB::Query('SELECT * FROM #users')   // # replaced with cfg('db/prefix')
```

### Increment/Decrement Operations
```php
DB::Update('users', ['id' => 1], ['score+' => 10]);  // score = score + 10
DB::Update('users', ['id' => 1], ['lives-' => 1]);   // lives = lives - 1
```

### Caching
```php
$users = DB::GetCached('SELECT * FROM users WHERE active = 1');
// Subsequent calls return cached results
```

### Statistics
```php
DB::$affectedRows                   // Rows affected by last operation
DB::$readOps                        // Count of read operations
DB::$writeOps                       // Count of write operations
DB::$lastQuery                      // Last executed query
```

## Examples

### Basic Usage
```php
// Insert new user
$userId = DB::Insert('users', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'active' => 1
]);

// Get user by ID
$user = DB::GetRow('users', $userId);

// Update user
DB::Update('users', ['id' => $userId], ['last_login' => date('Y-m-d H:i:s')]);

// Find users
$activeUsers = DB::Get('SELECT * FROM users WHERE active = ?', [1]);
```

### Complex Queries
```php
// Named parameters
$results = DB::Get('
    SELECT * FROM #posts 
    WHERE author_id = :author 
    AND created_date > :date 
    AND status = :status
', [
    'author' => $authorId,
    'date' => '2023-01-01',
    'status' => 'published'
]);

// Mixed parameter types
DB::Query('UPDATE #users SET score = score + ::points WHERE id = :id', [
    'points' => 100,    // Number (unescaped)
    'id' => $userId     // String (escaped)
]);
```

