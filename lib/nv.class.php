<?php

	class NV
	{
		
		static function get_filename_for_name($name)
		{
			$p = preg_replace("/[^[:alnum:]\/[:space:]]/u", '_', trim(strtolower($name)));
			$container = nibble('/', $p);
			if($container != '' && $p != '')
			{
				$p = $container.'/'.substr(md5($p), 0, 2).'/'.$p;
			}
			return('private/'.$p.'.json');
		}
	
		static function get($name, $default = array())
		{
			$d = json_decode(@file_get_contents(self::get_filename_for_name($name)), true);
			if(!$d) return($default);
			return($d);
		}

		static function set($name, $value)
		{
			$fn = self::get_filename_for_name($name);
			$seg = explode('/', $fn);
			array_pop($seg);
			$storage_path = implode('/', $seg);
			if(!file_exists($storage_path)) mkdir($storage_path, 0774, true);
			file_put_contents($fn, json_encode($value));
			return($value);
		}

		static function delete($name)
		{
			unlink(self::get_filename_for_name($name));
		}
		
	}
