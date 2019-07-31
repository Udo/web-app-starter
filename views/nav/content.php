<? return(function($prop) { ?>

<style>
  
  .content-pane {
    position: fixed;
    left: 248px;
    top: 0;
    bottom: 0;
    right: 0;
    background: white;
  }
  
</style>

<div <?= $prop['$'] ?> class="content-pane">
  
  <? print_r(URL::$route); ?>
  
</div><? });
  
