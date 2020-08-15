<?php User::require_login(); ?>

<h1>Change Nickname</h1>
<div class="block">
	
	<?= component('form/form', array('name' => 'changenick', 'fields' => array(
		
		'nick' => array(
			'type' => 'string',
			'title' => 'Nickname',
			'value' => first($_REQUEST['nick'], User::$data['nick']),
			'validate' => 'mandatory',
		),
		'text' => array(
			'type' => 'html',
			'value' => 'Warning: if you change your nickname, your old nickname becomes available to other people.
						You may not be able to get it back.',
		),
		'submit' => array(
			'type' => 'submit',
			'title' => 'Request change',
		),
		
	), 'ondata' => function($data, &$form) {
		
		$onick = $data['nick'];
		$form['data']['nick'] = $data['nick'] = User::sanitize_nick($data['nick']);
		$existing_nick = User::get_nick_info($data['nick']);
		if(sizeof($existing_nick) != 0)
		{
			$form['errors']['nick'] = 'This username already exists';
		}
		else if($onick != $form['data']['nick'])
		{
			$form['errors']['nick'] = 'We removed some invalid characters, would this suggestion be okay for you?';
		}
		else
		{
			User::set_nick_info($data['nick'], User::$data);
			User::save();
			URL::redirect('account');
		}
		
	})) ?>

</div>
