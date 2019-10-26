<?php
  
  include('config/settings.php');
  include('lib/ulib.php');
  include('lib/render.php');
  
  URL::MakeRoute();
    
  component(URL::$route['page'], $_REQUEST['attr']['id']);
  
  Log::audit('page:'.URL::$route['page'], URL::$route['l-path']);