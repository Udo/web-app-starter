<?php

	class Renderer
	{
		static $id_counter = 1000;
		static $render_funcs = array();
		static $call_stack = array();
		
		static function get_func($file_name)
		{
			if(!isset(self::$render_funcs[$file_name])) 
			{
				ob_start();
				if(file_exists('views/'.$file_name.'.php'))
				{
					$f = require('views/'.$file_name.'.php');
				}
				else if(file_exists('views/'.$file_name.'/index.php'))
				{
					$f = require('views/'.$file_name.'/index.php');
				}
				else
				{
					$f = function() use($file_name, $rcontent) {
					return('component not found: '.$file_name);
				};
				}
				$rcontent = ob_get_clean();
				if(!is_callable($f)) $f = function() use($file_name, $rcontent) {
					return($rcontent);
				};
				self::$render_funcs[$file_name] = $f;
			}
			return(self::$render_funcs[$file_name]);
		}
	}
	
	function component($file_name, $id_override = false, $prop = array())
	{
	$f = Renderer::get_func($file_name);
	$prop['id'] = $id_override ? $id_override : 'c'.(Renderer::$id_counter++);
	$prop['filename'] = $file_name;
	Renderer::$call_stack[] = &$prop;
	$result = $f($prop);
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