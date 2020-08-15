<?php return(function($prop) {
	
	?><div>
		<label><?= htmlspecialchars($prop['title']) ?></label>
		<div><?= htmlspecialchars(first($prop['value'], $prop['default'])) ?></div>
	</div><?php

	if($prop['error']) 
	{
		?><div>
			<label></label>
			<?= component('elements/error', array('text' => $prop['error'])) ?>
		</div><?php
	}
		
});
