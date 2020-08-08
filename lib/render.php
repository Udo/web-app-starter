<?php
	
	function error_banner($s)
	{
		?><div class="banner"><?= htmlspecialchars($s) ?></div><?
	}
	
	class Renderer
	{
		static $id_counter = 1000;
		static $render_funcs = array();
		static $call_stack = array();
		
		static function get_func($file_name, $return_false_if_not_found = false)
		{
			if(!isset(self::$render_funcs[$file_name])) 
			{
				ob_start();
				if(file_exists('views/'.$file_name.'.php'))
				{
					$f = require('views/'.$file_name.'.php');
					URL::$route['components-invoked'][] = 'views/'.$file_name.'';
				}
				else if(file_exists('views/'.$file_name.'/index.php'))
				{
					$f = require('views/'.$file_name.'/index.php');
					URL::$route['components-invoked'][] = 'views/'.$file_name.'/index';
				}
				else
				{
					if($return_false_if_not_found) return(false);
					$f = function() use($file_name, $rcontent) {
						return('<div class="banner">component not found: '.$file_name.'</div>');
					};
				}
				$rcontent = ob_get_clean();
				if(!is_callable($f)) 
				{
					$f = function() use($file_name, $rcontent) {
						print($rcontent);
					};
				}
				else 
				{
					print($rcontent);
				}
				self::$render_funcs[$file_name] = $f;
			}
			return(self::$render_funcs[$file_name]);
		}
	}
	
	function component_path($file_name, $prop = array(), $opt = array())
	{
		$f = Renderer::get_func($file_name, true);
		if($f) return(component($file_name, $prop, $opt));
		$fpath = array();
		$seg = explode('/', $file_name);
		array_unshift($seg, '');
		$tried = array();
		$possible_paths = array();
		$path_current = '';
		foreach($seg as $idx => $s) if($s != '' || $idx == 0) 
		{
			$path_current .= ($idx > 0 ? '/' : '').$s;
			$possible_paths[] = $path_current;
		}
		foreach(array_reverse($possible_paths) as $fpath) if(!$was_handled)
		{
			$fn = 'views'.$fpath.'/_path';
			$tried[] = htmlspecialchars($fn);
			if(file_exists($fn.'.php'))
			{
				$was_handled = include($fn.'.php');
				if($was_handled) URL::$route['components-invoked'][] = $fn;
			}
		}
		if(!$was_handled)
		{
			URL::$route['errors'][] = array('type' => 'component not found', 'file' => $file_name);
			return(error_banner('Component not found: '.htmlspecialchars($file_name).
				'<br/>Also tried:<br/>&nbsp; - '.implode('<br/>&nbsp; - ', $tried)));
		}
	}

	function component_open($file_name, $prop = array(), $opt = array())
	{
		$prop['open'] = true;
		return(component($file_name, $prop, $opt));
	}
	
	function component_close($file_name, $prop = array(), $opt = array())
	{
		$prop['close'] = true;
		return(component($file_name, $prop, $opt));
	}
	
	function component_open_close($file_name, $prop = array(), $opt = array())
	{
		$prop['open'] = true;
		$prop['close'] = true;
		return(component($file_name, $prop, $opt));
	}
	
	function component($file_name, $prop = array(), $opt = array())
	{
		if($file_name == '')
		{
			URL::$route['errors'][] = array('type' => 'component not found', 'file' => $file_name);
			return(error_banner('Component not found: '.htmlspecialchars($file_name)));
		}
		$f = Renderer::get_func($file_name);
		$prop['id'] = $prop['id'] ? $prop['id'] : 'c'.(Renderer::$id_counter++);
		$prop['filename'] = $file_name;
		Renderer::$call_stack[] = &$prop;
		$result = $f($prop, $opt);
		array_pop(Renderer::$call_stack);
		return($result);
	}
	
	function update($plevel)
	{
		$prop = &Renderer::$call_stack[sizeof(Renderer::$call_stack)-(1+$plevel)];
		return('K.update('.str_replace('"', '\'', json_encode($prop['id'])).');');
	}
	
	function ap(&$prop) # auto props
	{
		return('id="'.$prop['id'].'" data-com="'.$prop['filename'].'"');
	}
	
	function banner($s)
	{
		print('<div class="banner">'.$s.'</div>');
	}
