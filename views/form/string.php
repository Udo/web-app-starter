<?php return(function($prop) {
	
	?><div>
		<label><?= htmlspecialchars($prop['title']) ?></label>
		<input type="string" name="<?= $prop['field'] ?>" placeholder="<?= htmlspecialchars($prop['placeholder']) ?>" value="<?= htmlspecialchars(first($prop['value'], $prop['default'])) ?>"/>
	</div><?php

	if($prop['error']) 
	{
		?><div>
			<label></label>
			<?= component('elements/error', array('text' => $prop['error'])) ?>
		</div><?php
	}
		
});