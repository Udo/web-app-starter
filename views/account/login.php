<h1>Log In</h1>

<div class="block">
	
	<?= component('form/form', array('name' => 'login', 'fields' => array(
		
		'email' => array(
			'type' => 'string',
			'title' => 'Email',
			'value' => $_REQUEST['email'], 
			'placeholder' => 'you@example.com',
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
		
		if(User::try_login($data['email'], $data['password']))
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
		
		'email' => array(
			'type' => 'string',
			'title' => 'Email',
			'value' => $_REQUEST['email'], 
			'placeholder' => 'you@example.com',
			'validate' => 'mandatory',
		),
		'nick' => array(
			'type' => 'string',
			'title' => 'Nick',
			'value' => $_REQUEST['nick'], 
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
		
		if(User::try_create_account($data['email'], $form['data']['nick'], $data['password'], $data['password2']))
		{
			User::try_login($data['email'], $data['password']);
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
