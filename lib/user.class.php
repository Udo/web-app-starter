<?php

	class User
	{
		
		static function is_logged_in()
		{
			return($_SESSION['uid']);
		}
		
		static function require_login()
		{
			if(!self::is_logged_in())
			{
				URL::redirect(URL::link('login', array('whence' => URL::$route['l-path'])));
			}
		}
		
		static function try_login($username, $password)
		{
			
		}
		
	}
