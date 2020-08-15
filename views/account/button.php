<?php return(function() {
	
	if(!User::is_logged_in()) 
	{ 
		?><a style="float:right;" href="<?= URL::link('account/login') ?>">Log in</a><?php 
	}
	else
	{
		?>
		<a style="float:right;" href="<?= URL::link('account/logout') ?>">Log out</a>
		<a style="float:right;" href="<?= URL::link('account') ?>"><?= 
			first(User::$data['nick'], User::$data['email']) ?></a>
		<?php 
	}
	
});
