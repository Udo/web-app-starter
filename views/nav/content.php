<? return(function($prop) { ?>

<div <?= ap($prop) ?> class="content-pane">

	<?= component('screens/'.URL::$route['l-path']) ?>
	
</div><? });
  
