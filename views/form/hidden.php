<?php return(function($prop) {
	
	?><input type="hidden" name="<?= $prop['field'] ?>" value="<?= htmlspecialchars(first($prop['value'])) ?>"/><?php
		
});