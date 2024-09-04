<?php return(function($prop) {
	
	?><input type="hidden" name="<?= $prop['field'] ?>" value="<?= htmlspecialchars($prop['value']) ?>"/><?php
		
});