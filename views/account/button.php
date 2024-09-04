<?php return(function() {
	
	if(!User::is_logged_in()) 
	{ 
		?><a style="float:right;" href="<?= URL::link('account/login') ?>">
			<i class="fa fa-sign-in-alt"></i> Sign in</a><?php 
	}
	else
	{
		?>
		<a style="float:right;" href="<?= URL::link('account') ?>">
			<i class="fa fa-user-circle"></i>
			<?= first(User::$data['nick'], User::$data['email']) ?></a>
		<?php 
	}
	
});
