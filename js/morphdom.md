# morphdom.js

A fast and lightweight DOM diffing/patching library for efficiently updating the DOM with minimal changes.

## Basic Usage

```javascript
morphdom(fromNode, toNode, options)     // Transform fromNode to match toNode
```

Returns the morphed node (may be a new node if root element changes).
Instead of replacing entire DOM trees, morphdom intelligently compares and patches only the differences:

```javascript
const container = document.getElementById('container');
const newHTML = '<div><p>Updated content</p></div>';

// Efficiently update DOM - only changed parts are modified
morphdom(container, newHTML);
```

## Options

```javascript
morphdom(fromNode, toNode, {
    childrenOnly: true,                 // Only morph children, not root element
    getNodeKey: node => node.id,        // Custom key function for matching nodes
    onBeforeNodeAdded: node => true,    // Called before adding new nodes
    onNodeAdded: node => {},            // Called after nodes are added
    onBeforeElUpdated: (from, to) => true, // Called before updating elements
    onElUpdated: node => {},            // Called after elements are updated
    onBeforeNodeDiscarded: node => true, // Called before removing nodes
    onNodeDiscarded: node => {},        // Called after nodes are removed
    onBeforeElChildrenUpdated: (from, to) => true, // Called before updating children
    skipFromChildren: (from, to) => false, // Skip children comparison
    addChild: (parent, child) => parent.appendChild(child) // Custom child addition
});
```

## Key Matching

Use `getNodeKey` for efficient list updates:

```javascript
// HTML with keyed elements
const html = `
    <ul>
        <li id="item-1">Item 1</li>
        <li id="item-2">Item 2</li>
        <li id="item-3">Item 3</li>
    </ul>
`;

morphdom(list, html, {
    getNodeKey: node => node.getAttribute('id')
});
```

## Event Handlers

```javascript
morphdom(container, newHTML, {
    onBeforeElUpdated: (fromEl, toEl) => {
        // Preserve event listeners
        if (fromEl.hasEventListeners) {
            return false; // Skip this element
        }
        return true;
    },
    
    onNodeAdded: (node) => {
        // Initialize new components
        if (node.classList?.contains('widget')) {
            initializeWidget(node);
        }
    }
});
```

## Form Element Handling

Morphdom has special handling for form elements:

- **INPUT**: Preserves checked, disabled, and value properties
- **TEXTAREA**: Syncs value and text content
- **SELECT**: Maintains selectedIndex and option states
- **OPTION**: Handles selected state in select boxes

## Common Patterns

### Template Updates
```javascript
function updateTemplate(container, data) {
    const template = `<div class="user">${data.name}</div>`;
    morphdom(container, template);
}
```

### List Management
```javascript
function updateList(listEl, items) {
    const html = items.map(item => 
        `<li id="item-${item.id}">${item.name}</li>`
    ).join('');
    
    morphdom(listEl, `<ul>${html}</ul>`, {
        childrenOnly: true,
        getNodeKey: node => node.id
    });
}
```

### Component Preservation
```javascript
morphdom(container, newHTML, {
    onBeforeElUpdated: (from, to) => {
        // Preserve components that haven't changed
        if (from.dataset.component === to.dataset.component) {
            return false; // Skip update
        }
        return true;
    }
});
```

## Integration with uquery.js

This library is used by uquery's `html()` method when differential updates are enabled:

```javascript
$.options.alwaysDoDifferentialUpdate = true;
$('#container').html(newContent); // Uses morphdom internally
```

## Browser Support

- Modern browsers with DOM Level 2+ support
- Automatic fallbacks for older browsers
- Template element support detection
- Range API support detection

