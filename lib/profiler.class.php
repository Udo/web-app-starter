<?php
  
class Profiler
{

	static $log = array();
	static $start = 0;
	static $last = 0;
	static $current = 0;
	static $indent_str = '';
	static $indent_level = 0;

	# makes a commented profiler entry 
	static function log($text, $indent_delta = 0)
	{
		$thistime = microtime(true);
		$absoluteMS = $thistime - self::$start;
		self::$indent_level += $indent_delta;
		if($indent_delta < 0) // if less than zero, update indent before logging
			self::$indent_str = str_repeat(' ', self::$indent_level * 2);
		if($text) self::$log[] = 
			number_format($absoluteMS*1000, 3).'ms | '.
			number_format(1000*($thistime - self::$last), 3).'ms | '.
			ceil(memory_get_usage()/1024).' kB | '.self::$indent_str.$text;
		if($indent_delta > 0) // if greater than zero, update indent after logging
			self::$indent_str = str_repeat(' ', 2*max(0, strlen(self::$indent_str) + $indent_delta));
		self::$last = $thistime;
		self::$current = $absoluteMS;
		return($thistime);
	}

	static function get_time()
	{
		return(1000*(microtime(true) - self::$start));
	}

	static function start()
	{
		self::$start = self::$last = microtime(true);
	}

}
