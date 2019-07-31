<? return(function($prop) { 
  
  $items = array();
  $items[] = array('url' => '/', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-inbox');
  $items[] = array('url' => '/1', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-cubes');
  $items[] = array('url' => '/2', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-folder');
  $items[] = array('url' => '/3', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-hockey-puck');
  $items[] = array('url' => '/4', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-bolt');
  $items[] = array('url' => '/4', 'title' => 'Item 1', 'icon' => 'fa fa-2x fa-question-circle');
  
?>

<style>
  
  .shortcut-bar {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: 48px;
  }
  
  .shortcut-bar > * {
    color: white;
    display: block;
    padding: 12px;
    text-align: center;
  }

  .shortcut-bar > a:hover {
    color: white;
    background: rgba(255,255,255,0.5);
  }
  
</style>

<div <?= $prop['$'] ?> class="shortcut-bar"><?php
  
  foreach($items as $item)
  {
    ?><a href="<?= $item['url'] ?>" title="<?= htmlspecialchars($item['title']) ?>"><i class="<?= $item['icon'] ?>"></i></a><?
  }
  
?></div><? });