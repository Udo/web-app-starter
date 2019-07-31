<?php
  
  class Renderer
  {
    static $id_counter = 1000;
    static $render_funcs = array();
    static $call_stack = array();
    
    static function get_func($file_name)
    {
      if(!isset(self::$render_funcs[$file_name])) 
        self::$render_funcs[$file_name] = require('views/'.$file_name.'.php');
      return(self::$render_funcs[$file_name]);
    }
  }
  
  function component($file_name, &$prop = array())
  {
    $f = Renderer::get_func($file_name);
    $prop['id'] = 'c'.(Renderer::$id_counter++);
    $prop['$'] = 'id="'.$prop['id'].'" data-com="'.$file_name.'"';
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