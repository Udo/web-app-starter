<?php return(function($prop) {
	
	?><div>
		<label></label>
		<input type="submit" name="submit" value="<?= htmlspecialchars(first($prop['title'])) ?>"/>
		<div></div>
	</div><?php
		
});