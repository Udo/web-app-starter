<?php
	
	class Log
	{
		
		static function make($module, $text)
		{
			if(is_array($text))
				$text = str_replace(array('"', '\\'), '', json_encode($text));
			$seg = array();
			$seg[] = first($_SESSION['username'], 'anonymous');
			$seg[] = $_SERVER['REMOTE_ADDR'];
			$seg[] = round(memory_get_peak_usage()/1024).'kB';
			$seg[] = round(Profiler::get_time()).'ms';
			$seg[] = $module;
			$seg[] = $text;
			return($seg);			
		}

	    static function audit($module, $text = '', $class = 'warning')
	    {
		    $seg = self::make($module, $text);
			shell_exec('echo '.escapeshellarg(implode('|', $seg)).' | systemd-cat -t erpnxt -p '.escapeshellarg($class));
	    }
		
		static function debug($module, $text)
		{
			write_to_file('log/debug.'.gmdate('Y-m').'.log', 
				implode(chr(9), self::make($module, $text)).chr(10));
		}
		
	}
	
