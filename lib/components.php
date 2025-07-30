<?php

    // Example component definition file used by the components system:
    /*<?php return [

        'begin' => function($prop, &$context) {
            $context['current_component_id'] = $prop['id'];
            return('<div class="my-component">');
        },

        'end' => function($prop) {
            ?><script>
                console.log('Component <?= htmlspecialchars($prop['id']) ?> finalized');
            </script><?php
            return('</div>');
        },

        'render' => function($prop, &$context) {
            return($context['begin']($prop, $context) . $context['end']($prop));
        },

        'about' => 'This is an example component that wraps content in a div and logs when it is finalized.',

    ]; */

    $GLOBALS['render_funcs'] = array();

	function component_component_error_banner($s)
	{
		?><div class="banner"><?= htmlspecialchars($s) ?></div><?
	}

    /**
     * Loads a component from file system and registers it in the global registry
     * 
     * Component files are searched in this order:
     * 1. components/{component_name}.php
     * 2. {search_path}/{component_name}.php (if search_path provided)
     * 3. interface/{component_name}.php
     * 
     * Component files should return an array with render functions like:
     * return ['render' => function($prop) { ... }];
     */
    function component_get_func($file_name, $return_false_if_not_found = false, $search_path = false)
    {
        $result = [];
        // Check if component is already loaded in global registry
        if(!isset($GLOBALS['render_funcs'][$file_name]))
        {
            if(file_exists($file_name.'.php'))
            {
                $result['component_file'] = $file_name.'.php';
            }
            else
            {
                // Component file not found - create error component
                if($return_false_if_not_found) return(false);
                $result['component_file'] = false;
                $result['error'] = 'Component not found: '.$file_name;
                $result['render'] = function() use($file_name, $rcontent, $search_path) {
                    return('<div class="banner">component not found: '.$file_name.'</div>');
                };
            }
            if($result['component_file'])
            foreach(require($result['component_file']) as $k => $v)
                $result[$k] = $v;
            // Register component in global registry for future use
            $GLOBALS['render_funcs'][$file_name] = $result;
        }
        return($GLOBALS['render_funcs'][$file_name]);
    }

/**
 * Checks if a component exists without loading it
 * Returns true if component file can be found, false otherwise
 */
function component_exists($file_name)
{
	$caller_dir = dirname(debug_backtrace()[0]['file']);
	return(component_get_func($file_name, true, $caller_dir) !== false);
}

/**
 * Loads a component and returns its definition
 * Same as component_get_func but with caller directory detection
 */
function component_load($file_name)
{
	$caller_dir = dirname(debug_backtrace()[0]['file']);
	return(component_get_func($file_name, true, $caller_dir));
}

/**
 * Declares an inline component directly in code (not from file)
 * 
 * @param string $file_name - Component name for registry
 * @param array $render_prop - Component definition with render functions
 * 
 * Example:
 * component_declare('my-button', [
 *     'render' => function($prop) { return "<button>{$prop['text']}</button>"; }
 * ]);
 */
function component_declare($file_name, $render_prop)
{
    // Register the inline component directly in global registry
    $GLOBALS['render_funcs'][$file_name] = $render_prop;
}

/**
 * Main component rendering function - the heart of the component system
 * 
 * @param string $file_name - Component name (can include render method like 'comp:method')
 * @param array $prop - Properties/data to pass to component
 * @return mixed - Component output (HTML string or structured data)
 * 
 * Component calling examples:
 * component('button', ['text' => 'Click me'])
 * component('section:begin', ['options' => [...]]) use the 'begin' render function
 * component('section:end'])   use the 'end' render function
 * component('form', ['return_struct' => true]) // Returns structured data
 */
function component($file_name, $prop = array())
{
    Profiler::log('component '.$file_name, 1);
	$caller_dir = dirname(debug_backtrace()[0]['file']);
	if($file_name == '')
		return(component_error_banner('[component name empty]'));

    // Default render method is 'render', but can be overridden
    $prop['render_call'] = first($prop['render_call'], 'render');
    
    // Support for calling specific render methods: 'component:method'
    if(stristr($file_name, ':')) // you can specify which render function to call
    {
        $prop['render_call'] = $file_name;
        $file_name = nibble(':', $prop['render_call']);
    }
    
    // Get component definition from registry (load if not already loaded)
	$renderer = &$GLOBALS['render_funcs'][$file_name];
    if(!$renderer) 
    {
        // Component not in registry - try to load from file
        component_get_func($file_name, false, $caller_dir);
    	$renderer = &$GLOBALS['render_funcs'][$file_name];
    }

    // Generate unique ID for component instance if not provided
	$prop['id'] = $prop['id'] ? $prop['id'] : 'c'.($GLOBALS['id_counter']++);
	$prop['filename'] = $file_name;

    if(is_callable($renderer[$prop['render_call']]))
        $result = ($renderer[$prop['render_call']]($prop, $renderer));
    else
        $result = ($renderer[$prop['render_call']]);

    Profiler::log(false, -1);
    return($result);
}

