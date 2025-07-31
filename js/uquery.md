# uquery.js

A lightweight DOM utility library inspired by jQuery's best features.

## Core Selector

```javascript
$(selector)  // Returns NodeList/Array for chaining
```

Works with CSS selectors, elements, or other objects.

## DOM Manipulation

```javascript
$(selector).html(content)           // Get/set innerHTML with morphdom diffing
$(selector).append(html)            // Append content
$(selector).remove()                // Remove elements
$(selector).replaceWith(content)    // Replace with new content
$(selector).query(selector)         // Find descendants
```

## Styling & Classes

```javascript
$(selector).css({prop: value})      // Set CSS properties
$(selector).addClass('class')       // Add CSS class(es)
$(selector).removeClass('class')    // Remove CSS class(es)
$(selector).hide()                  // Hide elements
$(selector).show()                  // Show elements
$(selector).toggle()                // Toggle visibility
```

## Events

```javascript
$(selector).on(event, handler)      // Add event listener
$(selector).off(event, handler)     // Remove event listener
```

## Utilities

```javascript
$(selector).each(callback)          // Iterate over elements
$(selector).parent()                // Get parent elements
$(selector).load(url, options)      // Load content via AJAX
```

## AJAX

```javascript
$.get(url, callback)
$.post(url, data, callback)
$.ajax({
    url: '/api/endpoint',
    method: 'POST',
    data: {key: 'value'},
    success: response => {},
    error: xhr => {}
})
```

## Global Events

```javascript
$.on(topic, handler)                // Subscribe to custom events
$.off(topic, handler)               // Unsubscribe
$.emit(topic, ...args)              // Emit custom events
$.ready(callback)                   // DOM ready
```

## Options

```javascript
$.options.alwaysDoDifferentialUpdate = true  // Use morphdom for updates
```

## EventEmitter Class

Standalone event system with slot-based deduplication:

```javascript
const emitter = new EventEmitter();
const unsub = emitter.on('topic', handler, 'slot_key');
emitter.emit('topic', data);
unsub(); // or emitter.off('topic', 'slot_key');
```

