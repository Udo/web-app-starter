<?php
  
  include('config/settings.php');
  include('lib/ulib.php');
  include('lib/render.php');
  
  URL::MakeRoute();
  
  if(!$_REQUEST['data-com']) $_REQUEST['data-com'] = 'start';
  
  component($_REQUEST['data-com']);