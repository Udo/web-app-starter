<?php

	class User
	{
		
		static $last_error = false;
		static $data = array();
		
		static function init()
		{
			if($_SESSION['uid'])
				self::$data = self::get($_SESSION['uid']);
		}
		
		static function is_logged_in()
		{
			return($_SESSION['uid']);
		}
		
		static function require_login()
		{
			if(!self::is_logged_in())
			{
				URL::redirect('account/login', array('whence' => URL::$route['l-path']));
			}
		}
		
		static function email_to_uid($email)
		{
			return(alnum($email));
		}
		
		static function get($uid)
		{
			return(NV::get('accounts/'.$uid.'/info'));
		}
		
		static function get_nick_info($nick)
		{
			return(NV::get('nick/'.self::sanitize_nick($nick)));
		}
		
		static function set_nick_info($nick, &$account)
		{
			if(!$account) return;
			$nick = self::sanitize_nick($nick);
			if(self::get_nick_info($nick))
			{
				self::$last_error = 'This nickname is already in use';
				return;
			}
			$previous_nick = $account['nick'];
			$account['nick'] = $nick;
			NV::delete('nick/'.$previous_nick);
			NV::set('nick/'.$nick, array(
				'uid' => $account['uid'],
				'nick' => $nick,
			));
			return(true);
		}
		
		static function sanitize_nick($nick)
		{
			return(substr(trim(alnum($nick, '_', false)), 0, 32));
		}
		
		static function logout()
		{
			unset($_SESSION['uid']);
			self::$data = array();
		}
		
		static function try_login($email, $password)
		{
			$data = self::get(self::email_to_uid($email));
			if(sizeof($data) == 0)
			{
				self::$last_error = 'Account not found';
				return;
			}
			if(!password_verify($password, $data['password']))
			{
				self::$last_error = 'Credentials invalid';
				return;
			}
			if($data['banned'])
			{
				self::$last_error = 'This account is banned';
				return;
			}
			session_start();
			$_SESSION['uid'] = $data['uid'];
			self::$data = $data;
			return(true);
		}
		
		static function save()
		{
			if(!self::is_logged_in()) return;
			NV::set('accounts/'.$_SESSION['uid'].'/info', self::$data);
		}
		
		static function try_create_account($email, &$nick, $password1, $password2)
		{
			if($password1 != $password2)
			{
				self::$last_error = 'The passwords you entered do not match';
				return;
			}
			$existing_account = self::get($email);
			if(sizeof($existing_account) != 0)
			{
				self::$last_error = 'This email address is already in use';
				return;
			}
			if($nick != self::sanitize_nick($nick))
			{
				self::$last_error = 'Your nickname may only contain alphanumerical characters';
				$nick = self::sanitize_nick($nick);
				return;
			}
			$existing_nick = self::get_nick_info($nick);
			if(sizeof($existing_nick) != 0)
			{
				self::$last_error = 'This nickname is already in use';
				return;
			}
			$data = NV::set('accounts/'.alnum($email).'/info', array(
				'email' => $email,
				'nick' => $nick,
				'uid' => alnum($email),
				'password' => password_hash($password1, PASSWORD_DEFAULT),
				'created_on' => time(),
				'created_info' => get_browser_info(),
				));
			self::set_nick_info($nick, $data);
			return($data);
		}

		static function try_change_password($password1, $password2)
		{
			if(!self::is_logged_in()) return;
			if($password1 != $password2)
			{
				self::$last_error = 'The passwords you entered do not match';
				return;
			}
			self::$data['previous_password'] = self::$data['password'];
			self::$data['password'] = password_hash($password1, PASSWORD_DEFAULT);
			self::$data['password_changed_on'] = time();
			self::save();
			return(true);
		}
		
	}
