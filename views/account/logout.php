<?php
User::Logout();
header('Location: '.URL::link('account/login'));
exit;
