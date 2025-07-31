<?php

spl_autoload_register(function ($class_name) {
	$classFile = 'lib/'.strtolower($class_name).'.class.php';
	if(file_exists($classFile))
	{
		include($classFile);
		return;
	}
});

Profiler::start();

function get_file_location($file, $error_if_not_found = true)
{
	if(file_exists($file)) return $file;
	foreach($GLOBALS['config']['site']['include_paths'] as $path)
	{
		if(file_exists($path.$file)) return $path.$file;
	}
	if($error_if_not_found)
		die('file not found: '.$file);
	return false;
}

function include_js($src_file)
{
	if(!($file_location = get_file_location($src_file))) return;
	?><script src="<?= cfg('url/root').$file_location ?>?v=<?= filemtime($file_location) ?>"></script><?
}

function include_css($src_file)
{
	if(!($file_location = get_file_location($src_file))) return;
	?><link rel="stylesheet" href="<?= cfg('url/root').$file_location ?>?v=<?= filemtime($file_location) ?>" /><?
}
	
# **************************** GENERAL UTILITY FUNCTIONS ******************************

// escapes a string for use in HTML attributes
function asafe($s)
{
	return htmlspecialchars(str_replace(array("\r", "\n"), ' ', $s), ENT_QUOTES, 'UTF-8');
}

// escapes a string for use in HTML text
function safe($s)
{
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function jsafe($s)
{
	return json_encode($s);
}

/**
	* Can have any number of arguments. Returns the first of its arguments that is not false, empty string, or null.
	*/ 
function first()
{
	$args = func_get_args();
	foreach($args as $v)
	{
		if(isset($v) && $v !== false && $v !== '' && $v !== null)
			return($v);
	}
	return('');
}

function alnum($s, $replace_with = '_', $trim = true)
{
	if($trim) $s = trim(strtolower($s));
	return(preg_replace("/[^[:alnum:][:space:]]/u", $replace_with, $s));
}

/** 
	* Append a string to the given file.
	*/
function write_to_file($filename, $content)
{
	if (is_array($content)) $content = json_encode($content);
	$open = fopen($filename, 'a+');
	fwrite($open, $content);
	fclose($open);
	@chmod($filename, 0777);
}

# **************************** ARRAY FUNCTIONS ******************************

/**
	* Returns a value from the $GLOBALS['config'] array identified by $key.
	* Sub-array values can be addressed by using the '/' character as a separator.
	*/
function cfg($key)
{
	$config = $GLOBALS['config'];
	$seg = explode('/', $key);
	$lastSeg = array_pop($seg);
	foreach($seg as $s)
	{
		if(is_array($config[$s]))
			$config = $config[$s];
		else
			$config = array();	
	}
	return($config[$lastSeg]);
}


# **************************** STRING/FORMATTING FUNCTIONS ******************************

/**
	* Convert any base number into another number of another base system.
	*/
function base_convert_any($numberInput, $fromBaseInput, $toBaseInput)
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
			$base10=base_convert_any($numberInput, $fromBaseInput, '0123456789');
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

/**
	* Convert a Unix timestamp into a human-friendly short form.
	*/
function age_to_string($unixDate, $new = 'just now', $ago = 'ago')
{
	if($unixDate == 0) return('-');
	$result = '';
	$oneMinute = 60;
	$oneHour = $oneMinute*60;
	$oneDay = $oneHour*24;
	
	$difference = time() - $unixDate;
	
	if ($difference < $oneMinute)
		$result = $new;
	else if ($difference < $oneHour)
		$result = round($difference/$oneMinute).' min '.$ago;
	else if ($difference < $oneDay)
		$result = floor($difference/$oneHour).' h '.$ago;
	else if ($difference < $oneDay*5)
		$result = gmdate('D H:i', $unixDate);
	else if ($difference < $oneDay*365)
		$result = gmdate('M dS H:i', $unixDate);
	else
		$result = date('d. M Y H:i', $unixDate);
	return($result);
}

/**
	* Given the separator string $segdiv, cut a piece of &$cake off that precedes $segdiv,
	* and return that piece. If there are no instances of $segdiv in &$cake, nibble()
	* returns the entirety of &$cake and sets &$cake to an empty string.
	*/
function nibble($segdiv, &$cake, &$found = false)
{
	$p = strpos($cake, $segdiv);
	if ($p === false)
	{
		$result = $cake;
		$cake = '';
		$found = false;
	}
	else
	{
		$result = substr($cake, 0, $p);
		$cake = substr($cake, $p + strlen($segdiv));
		$found = true;
	}
	return $result;
}

function make_hash($s = false, $length = 10)
{
	if($s === false) $s = time().random_int(0, 2147483647);
	$s = strtolower(substr(trim($s), 0, 64));
	return(substr(base_convert_ex(
		sha1(sha1('qw0e983124o521öl34u9087'.$s)),
		'0123456789abcdef',
		'0123456789abcdefghijklmnopqrstuvwxyz'
	), -$length));
}