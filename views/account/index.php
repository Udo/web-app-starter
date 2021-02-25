<?php User::require_login(); ?>

<h1>Welcome, <?= first(User::$data['nick'], User::$data['email']) ?></h1>
<div>
	<a href="<?= URL::Link('account/password') ?>" class="btn">Change Password</a>
	<a href="<?= URL::link('account/logout') ?>" class="btn"> <i class="fa fa-sign-out-alt"></i> Sign out</a>

</div>
