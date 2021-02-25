<?php

class NV
{
	
	static $storage_root = 'private';
	
	static function base_convert_ex($numberInput, $fromBaseInput, $toBaseInput)
	{
		if ($fromBaseInput==$toBaseInput) return $numberInput;
		$fromBase = str_split($fromBaseInput,1);
		$toBase = str_split($toBaseInput,1);
		$number = str_split($numberInput,1);
		$fromLen=strlen($fromBaseInput);
		$toLen=strlen($toBaseInput);
		$numberLen=strlen($numberInput);
		$retval='';
		if ($toBaseInput == '0123456789')
		{
			$retval=0;
			for ($i = 1;$i <= $numberLen; $i++)
				$retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase),bcpow($fromLen,$numberLen-$i)));
			return $retval;
		}
		if ($fromBaseInput != '0123456789')
			$base10=self::base_convert_ex($numberInput, $fromBaseInput, '0123456789');
		else
			$base10 = $numberInput;
		if ($base10<strlen($toBaseInput))
			return $toBase[$base10];
		while($base10 != '0')
		{
			$retval = $toBase[bcmod($base10,$toLen)].$retval;
			$base10 = bcdiv($base10,$toLen,0);
		}
		return $retval;
	}

	static function make_hash($s)
	{
		$s = strtolower(substr(trim($s), 0, 64));
		return(substr(self::base_convert_ex(
			sha1(sha1('qw0e983124o521รถl34u9087'.$s)),
			'0123456789abcdef',
			'0123456789abcdefghijklmnopqrstuvwxyz'
		), -10));
	}
	
	static function make_bucket_path($p)
	{
		if(stristr($p, '/') !== false)
		{
			$seg = explode('/', $p);
			$p = array_shift($seg);
			return(substr($p, -2).'/'.$p.'/'.implode('/', $seg));
		}
		return(substr($p, -2).'/'.$p);
	}
	
	static function write_data($class, $bucket, $type, $data)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		if(!file_exists($storage_path)) @mkdir($storage_path, 0774, true);
		file_put_contents($storage_path.'/'.$type.'.json', json_encode($data));
		$GLOBALS['write_data'] = $storage_path.'/'.$type.'.json';
	}
	
	static function read_data($class, $bucket, $type)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		return(json_decode(@file_get_contents($storage_path.'/'.$type.'.json'), true));
	}
	
	static function get_data_filename($class, $bucket, $type)
	{
		return(self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket).'/'.$type.'.json');
	}
	
	static function list_bucket($class, $bucket)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		return(explode(chr(10), trim(shell_exec('ls -1 '.escapeshellarg($storage_path)))));
	}
	
	static function delete_bucket($class, $bucket)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		if(stristr($storage_path, '*') !== false) return;
		if(stristr($storage_path, '?') !== false) return;
		$result = trim(shell_exec('rm -r '.escapeshellarg($storage_path).' 2>&1'));
		return($result);
	}
	
	static function list_storage($class, $bucket, $crit = false)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		$result = array();
		if($crit)
			$crit_arg = '-iname '.escapeshellarg($crit);
		foreach(explode(chr(10), trim(shell_exec('cd '.escapeshellarg($storage_path).' && find . '.$crit_arg))) as $item)
		{
			nibble('/', $item);
			$file = $storage_path.'/'.$item;
			if(is_file($file))
			{
				if(stristr($item, '/') !== false)
				{
					$dir_parts = explode('/', $item);
					$item = array_pop($dir_parts);
					$sbucket = '/'.implode('/', $dir_parts);
				}
				else
				{
					$sbucket = '';
				}
				$format = 'none';
				if(substr($item, -4, 1) == '.')
				{
					$format = substr($item, -3);
					$item = substr($item, 0, -4);
				}
				$result[] = array(
					'bucket' => $bucket.$sbucket,
					'item' => $item,
					'format' => $format,
					'file' => $file,
				);
			}
		}
		return($result);
	}
	
	static function write_log($class, $bucket, $type, $data)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		if(!file_exists($storage_path)) @mkdir($storage_path, 0774, true);
		WriteToFile($storage_path.'/'.$type.'.log', json_encode($data).chr(10));
	}
	
	static function read_log($class, $bucket, $type, $line_count = 8, $offset = false)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		return(self::get_json_tail($storage_path.'/'.$type.'.log', $line_count, $offset));
	}
	
	static function read_log_complete($class, $bucket, $type)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		return(self::json_lines(file_get_contents($storage_path.'/'.$type.'.log', $line_count, $offset)));
	}
	
	static function search_log($class, $bucket, $type, $q, $max_lines = false)
	{
		$storage_path = escapeshellarg(self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket).'/').$type.'.log';
		$filter = '';
		if($max_lines > 0)
			$filter .= ' | tail -n '.$max_lines.' ';
		return(self::json_lines(trim(shell_exec('grep -Fhi '.escapeshellarg($q).' '.($storage_path).$filter))));
	}
	
	static function line_count($class, $bucket, $type)
	{
		$storage_path = self::$storage_root.'/'.$class.'/'.self::make_bucket_path($bucket);
		return(1*trim(shell_exec('wc -l '.escapeshellarg($storage_path.'/'.$type.'.log'))));
	}
		
	static function get_json_tail($from_file, $line_count = 8, $offset = false)
	{
		if($offset >  0)
		{
			$lines = trim(shell_exec(
				'tail -n '.escapeshellarg($offset+$line_count).' '.
				escapeshellarg($from_file).' | head -n '.escapeshellarg($line_count)));
		}
		else
		{
			$lines = trim(shell_exec(
				'tail -n '.escapeshellarg($line_count).' '.
				escapeshellarg($from_file)));
		}
		return(self::json_lines($lines));
	}
	
	static function get_tail($from_file, $line_count = 8, $offset = false)
	{
		if($offset >  0)
		{
			$lines = trim(shell_exec(
				'tail -n '.escapeshellarg($offset+$line_count).' '.
				escapeshellarg($from_file).' | head -n '.escapeshellarg($line_count)));
		}
		else
		{
			$lines = trim(shell_exec(
				'tail -n '.escapeshellarg($line_count).' '.
				escapeshellarg($from_file)));
		}
		return(explode(chr(10), $lines));
	}
	
	static function json_lines($lines)
	{
		if($lines == '')
		{
			return(array());
		}
		else
		{
			$result = array();
			foreach(explode(chr(10), $lines) as $line)
				$result[] = json_decode($line, true);
			return($result);
		}
	}
		
}
