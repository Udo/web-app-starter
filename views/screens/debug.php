<? return(function($prop) { ?>

<div <?= ap($prop) ?> class="content-pane"><pre>
  
  <? print_r($prop); print_r($_REQUEST); print_r(URL::$route); ?>
  
</pre></div><? });
  
