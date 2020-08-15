<?php User::require_login(); ?>

<h1>Welcome, <?= first(User::$data['nick'], User::$data['email']) ?></h1>
<div>
	<a href="<?= URL::Link('account/nick') ?>" class="btn">Change Nickname</a>
	<a href="<?= URL::Link('account/password') ?>" class="btn">Change Password</a>
</div>
