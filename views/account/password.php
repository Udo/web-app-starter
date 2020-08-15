<?php User::require_login(); ?>

<h1>Change Password</h1>
<div class="block">
	
	<?= component('form/form', array('name' => 'changepwd', 'fields' => array(
		
		'password' => array(
			'type' => 'password',
			'title' => 'New Password',
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
			'title' => 'Change password',
		),
		
	), 'ondata' => function($data, &$form) {
		
		if(User::try_change_password($data['password'], $data['password2']))
		{
			URL::redirect('account');
		}
		else
		{
			print(component('elements/error', array(
				'text' => first(User::$last_error, 'Could not change your password'))));			
		}
		
	})) ?>

</div>
