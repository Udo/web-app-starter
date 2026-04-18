<?php

    // Example component definition file used by the components system:
    /*<?php return [

        'begin' => function($prop, &$context) {
            $context['current_component_id'] = $prop['id'];
            return('<div class="my-component">');
        },

        'end' => function($prop) {
            ?><script>
                console.log('Component <?= safe($prop['id']) ?> finalized');
            </script><?php
            return('</div>');
        },

        'render' => function($prop, &$context) {
            return($context['begin']($prop, $context) . $context['end']($prop));
        },

        'about' => 'This is an example component that wraps content in a div and logs when it is finalized.',

    ]; */

    $GLOBALS['render_funcs'] = array();
    $GLOBALS['id_counter'] = $GLOBALS['id_counter'] ?? 1;

    function component_error_banner($s)
	{
        return '<div class="banner">'.safe($s).'</div>';
	}

    function component_caller_dir()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        return isset($trace[1]['file']) ? dirname($trace[1]['file']) : getcwd();
    }

    function component_normalize_name($file_name)
    {
        return preg_replace('/\.php$/', '', trim((string)$file_name));
    }

    function component_resolve_file($file_name, $search_path = false)
    {
        $component_name = component_normalize_name($file_name);
        $candidates = array($component_name.'.php');
        if($search_path && !str_starts_with($component_name, 'components/'))
            $candidates[] = rtrim($search_path, '/').'/'.$component_name.'.php';
        if(!str_starts_with($component_name, 'components/'))
            $candidates[] = 'components/'.$component_name.'.php';
        foreach(array_unique($candidates) as $candidate)
        {
            if(file_exists($candidate))
                return $candidate;
        }
        return false;
    }

    /**
     * Loads a component from file system and registers it in the global registry
     * 
    * Component files are searched in this order:
    * 1. exact file path passed to the loader
    * 2. caller-relative path (for shorthand component names)
    * 3. components/{component_name}.php
     * 
     * Component files should return an array with render functions like:
     * return ['render' => function($prop) { ... }];
     */
    function component_get_func($file_name, $return_false_if_not_found = false, $search_path = false)
    {
        $component_name = component_normalize_name($file_name);
        $result = [];
        // Check if component is already loaded in global registry
        if(!isset($GLOBALS['render_funcs'][$component_name]))
        {
            $component_file = component_resolve_file($component_name, $search_path);
            if($component_file)
            {
                $result['component_file'] = $component_file;
            }
            else
            {
                // Component file not found - create error component
                if($return_false_if_not_found) return(false);
                $result['component_file'] = false;
                $result['error'] = 'Component not found: '.$component_name;
                $result['render'] = function() use($component_name) {
                    return component_error_banner('component not found: '.$component_name);
                };
            }
            if($result['component_file'])
            foreach(require($result['component_file']) as $k => $v)
                $result[$k] = $v;
            // Register component in global registry for future use
            $GLOBALS['render_funcs'][$component_name] = $result;
        }
        return($GLOBALS['render_funcs'][$component_name]);
    }

/**
 * Checks if a component exists without loading it
 * Returns true if component file can be found, false otherwise
 */
function component_exists($file_name)
{
    $caller_dir = component_caller_dir();
	return(component_get_func($file_name, true, $caller_dir) !== false);
}

/**
 * Loads a component and returns its definition
 * Same as component_get_func but with caller directory detection
 */
function component_load($file_name)
{
    $caller_dir = component_caller_dir();
	return(component_get_func($file_name, true, $caller_dir));
}

function component_call($file_name, $render_call, $prop = array())
{
    $prop['render_call'] = $render_call;
    return component($file_name, $prop);
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
 * @param string $file_name - Component name or path
 * @param array $prop - Properties/data to pass to component
 * @return mixed - Component output from the selected render method
 * 
 * Component calling examples:
 * component('components/example/theme-switcher')
 * component_call('components/workspace/panel', 'render', ['title' => 'Status'])
 * component('workspace/panel', ['render_call' => 'render'])
 */
function component($file_name, $prop = array())
{
    Profiler::log('component '.$file_name, 1);
    $caller_dir = component_caller_dir();
	if($file_name == '')
		return(component_error_banner('[component name empty]'));
    $file_name = component_normalize_name($file_name);

    // Default render method is 'render', but can be overridden
    $prop['render_call'] = first($prop['render_call'] ?? false, 'render');
    
    // Support for calling specific render methods: 'component:method'
    if(stristr($file_name, ':')) // you can specify which render function to call
    {
        $prop['render_call'] = $file_name;
        $file_name = nibble(':', $prop['render_call']);
    }
    
    // Get component definition from registry (load if not already loaded)
    $renderer = $GLOBALS['render_funcs'][$file_name] ?? false;
    if(!$renderer) 
    {
        // Component not in registry - try to load from file
        component_get_func($file_name, false, $caller_dir);
        	$renderer = $GLOBALS['render_funcs'][$file_name] ?? false;
    }

    // Generate unique ID for component instance if not provided
    $prop['id'] = !empty($prop['id']) ? $prop['id'] : 'c'.($GLOBALS['id_counter']++);
	$prop['filename'] = $file_name;

    if(isset($renderer[$prop['render_call']]) && is_callable($renderer[$prop['render_call']]))
        $result = ($renderer[$prop['render_call']]($prop, $renderer));
    else if(isset($renderer[$prop['render_call']]))
        $result = ($renderer[$prop['render_call']]);
    else
        $result = component_error_banner('component render method not found: '.$file_name.':'.$prop['render_call']);

    Profiler::log(false, -1);
    return($result);
}

