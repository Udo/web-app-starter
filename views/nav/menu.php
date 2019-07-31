<? return(function($prop) { 
  
  $items = array();
  $items[] = array('url' => '', 'title' => 'Item 1', 'icon' => 'fa fa-start');
  
?>

<style>
  
  .menu-bar {
    position: fixed;
    left: 48px;
    top: 0;
    bottom: 0;
    width: 200px;
    background: rgba(255,255,255,0.8);
  }
  
  .menu-bar > a {
    display: block;
    padding: 12px;
    overflow: hidden;
    color: black;
    text-decoration: none;
  }

  .menu-bar > a:hover {
    background: rgba(0,55,155,0.5);
  }
  
</style>

<div <?= $prop['$'] ?> class="menu-bar"><?php
  
  foreach($items as $item)
  {
    ?><a href=""><i class=""></i> <?= htmlspecialchars($item['title']) ?></a><?
  }
  
?></div><? });