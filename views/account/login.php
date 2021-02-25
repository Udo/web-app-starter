<h1>Log In</h1>

<div class="block">
	
	<?= component('form/form', array('name' => 'login', 'fields' => array(
		
		'uid' => array(
			'type' => 'string',
			'title' => 'Username',
			'value' => $_REQUEST['uid'], 
			'placeholder' => 'your_name',
			'validate' => 'mandatory',
		),
		'password' => array(
			'type' => 'password',
			'title' => 'Password',
			'value' => $_REQUEST['password'], 
			'placeholder' => 'your password...',
			'validate' => 'mandatory',
		),
		'submit' => array(
			'type' => 'submit',
			'title' => 'Log in',
		),
		'whence' => array('type' => 'hidden', 'value' => $_REQUEST['whence']),
		
	), 'ondata' => function($data, &$form) {
		
		if(User::try_login($data['uid'], $data['password']))
		{
			URL::redirect(first($_REQUEST['whence'], ''));
		}
		else
		{
			print(component('elements/error', 
				array('text' => first(User::$last_error, 'Could not sign you in, please re-check your credentials and try again.'))));
		}
		
	})) ?>

</div>

<?php if(cfg('users/enable_signup')) { ?>
<h1>Create Account</h1>

<div class="block">

	<?= component('form/form', array('name' => 'signup', 'fields' => array(
		
		'uid' => array(
			'type' => 'string',
			'title' => 'Username',
			'value' => $_REQUEST['uid'], 
			'placeholder' => 'your_nickname',
			'validate' => 'mandatory',
		),
		'password' => array(
			'type' => 'password',
			'title' => 'Password',
			'value' => $_REQUEST['password'], 
			'placeholder' => 'your password...',
			'validate' => 'mandatory',
		),
		'password2' => array(
			'type' => 'password',
			'title' => '(repeat)',
			'value' => $_REQUEST['password2'], 
			'placeholder' => 'repeat your password...',
			'validate' => 'mandatory',
		),
		'submit' => array(
			'type' => 'submit',
			'title' => 'Sign up',
		),
		'whence' => array('type' => 'hidden', 'value' => $_REQUEST['whence']),
		
	), 'ondata' => function($data, &$form) {
		
		if(User::try_create_account($data['uid'], $data['password'], $data['password2']))
		{
			User::try_login($data['uid'], $data['password']);
			URL::redirect(first($_REQUEST['whence'], ''));
		}
		else
		{
			print(component('elements/error', array(
				'text' => first(User::$last_error, 'Could not create account'))));
		}
		
	})) ?>

</div>
<?php } ?>
