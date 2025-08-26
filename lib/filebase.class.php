<?php

class Filebase
{

    static function hash($s = false)
    {
        if($s === false) $s = time().random_int(0, 2147483647);
        $s = strtolower(substr(trim($s), 0, 64));
        return(substr(base_convert_ex(
            sha1(sha1('qw0e983124o521öl34u9087'.$s)),
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

    static function write_file($filename, $data)
    {
        $fp = fopen($filename, "c+");

        if(!$fp)
        {
            Log::text('<ERR>', 'file_put_contents_ex('.$filename.') could not write file');
            return;
        }

        if (flock($fp, LOCK_EX))
        {
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, $data);
        }
        else
        {
            Log::text('<ERR>', 'file_put_contents_ex('.$filename.') could not acquire lock');
        }

        fclose($fp);
    }

    static function delete_file($file_name)
    {
        unlink($file_name);
    }

    static function read_file($file_name)
    {
        $fsz = filesize($file_name);
        if($fsz == 0)
            return('');

        $fp = fopen($file_name, "rb+");

        if(!$fp)
            return('');

        if (flock($fp, LOCK_SH)) {
            $content = fread($fp, $fsz);
            flock($fp, LOCK_UN);
        } else {
            Log::text('<ERR>', 'read_file('.$file_name.') could not acquire lock');
        }

        fclose($fp);

        return($content);
    }

    static function write_data($class, $bucket, $type, $data)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        if(!file_exists($storage_path)) @mkdir($storage_path, 0774, true);
        $fn = $storage_path.'/'.$type.'.json';
        self::write_file($fn, json_encode($data));
    }

    static function read_data($class, $bucket, $type)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        $fn = $storage_path.'/'.$type.'.json';
        return(json_decode(self::read_file($fn), true));
    }

    static function delete_data($class, $bucket, $type)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        return(self::delete_file($storage_path.'/'.$type.'.json'));
    }

    static function get_data_filename($class, $bucket, $type)
    {
        return(first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket).'/'.$type.'.json');
    }

    static function list_bucket($class, $bucket)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        foreach(explode(chr(10), trim(shell_exec('ls -1 '.escapeshellarg($storage_path)))) as $name)
        {
            if(substr($name, 0, 1) != '_' && trim($name) != '')
                $items[] = $name;
        }
        return($items);
    }

    static function search_bucket($class, $bucket, $q)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        foreach(explode(chr(10), trim(shell_exec('grep -irlF '.escapeshellarg($q).' '.escapeshellarg($storage_path)))) as $l)
        {
            $name = substr($l, strlen($storage_path)+1, -5);
            if(substr($name, 0, 1) != '_' && trim($name) != '')
                $items[] = $name;
        }
        return($items);
    }

    static function delete_bucket($class, $bucket)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        if(stristr($storage_path, '*') !== false) return;
        if(stristr($storage_path, '?') !== false) return;
        $result = trim(shell_exec('rm -r '.escapeshellarg($storage_path).' 2>&1'));
        return($result);
    }

    static function write_log($class, $bucket, $type, $data)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        if(!file_exists($storage_path)) @mkdir($storage_path, 0774, true);
        WriteToFile($storage_path.'/'.$type.'.log', json_encode($data).chr(10));
    }

    static function read_log($class, $bucket, $type, $line_count = 8, $offset = false)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        return(get_json_tail($storage_path.'/'.$type.'.log', $line_count, $offset));
    }

    static function line_count($class, $bucket, $type)
    {
        $storage_path = first($GLOBALS['config']['filebase']['path'], 'data/').$class.'/'.self::make_bucket_path($bucket);
        return(intval(trim(shell_exec('wc -l '.escapeshellarg($storage_path.'/'.$type.'.log')))));
    }

    static function get_json_tail($from_file, $line_count = 8, $offset = false)
    {
        if($offset >  0)
        {
            $lines = trim(shell_exec(
                'tail -n '.escapeshellarg($offset+$line_count).' '.escapeshellarg($from_file).' | head -n '.escapeshellarg($line_count)));
        }
        else
        {
            $lines = trim(shell_exec(
                'tail -n '.escapeshellarg($line_count).' '.escapeshellarg($from_file)));
        }
        return(json_lines($lines));
    }

    static function get_tail($from_file, $line_count = 8, $offset = false)
    {
        $line_count = intval($line_count);
        if($offset >  0)
        {
            $lines = trim(shell_exec(
                'tail -n '.escapeshellarg($offset+$line_count).' '.escapeshellarg($from_file).' | head -n '.escapeshellarg($line_count)));
        }
        else
        {
            $lines = trim(shell_exec(
                'tail -n '.escapeshellarg($line_count).' '.escapeshellarg($from_file)));
        }
        return(explode(chr(10), $lines));
    }

    static function get_all_lines($from_file)
    {
        return(explode(chr(10), trim(file_get_contents($from_file))));
    }

    static function truncate_log($from_file, $lines = 128)
    {
        $lines = intval($lines);
        return(shell_exec('tail -n '.$lines.' '.escapeshellarg($from_file).' > /tmp/trunc ; cp /tmp/trunc '.escapeshellarg($from_file)));
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