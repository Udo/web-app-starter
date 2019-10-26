<? return(function($prop) { 
  
  $items = array();
  $items[] = array('m' => 'inbox', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-inbox');
  $items[] = array('m' => 'orders', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-cubes');
  $items[] = array('m' => 'inventory', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-folder');
  $items[] = array('m' => 'database', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-hockey-puck');
  $items[] = array('m' => 'tools', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-bolt');
  $items[] = array('m' => 'organization', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-question-circle');
  
?><div <?= ap($prop) ?> class="shortcut-bar"><?php
  
  foreach($items as $item)
  {
    ?><a onclick="K.update('menu', { m : '<?= $item['m'] ?>' });" 
	    title="<?= htmlspecialchars($item['title']) ?>"><i class="<?= $item['icon'] ?>"></i></a><?
  }
  
?></div><? });
	
	/*			
		
		*/