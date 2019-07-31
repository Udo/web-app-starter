<? return(function($prop) { ?>

<div <?= $prop['$'] ?> style="padding: 8px;background:green;">
  
  <?= time() ?>
  
  <button onclick="<?= update(0) ?>">DYNAMIC</button>
    
</div><? });
  
  