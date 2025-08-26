<?php

class User
{
    public static $session_key = 'user_id';

    public static $current_profile = null;

    static function LoadById($id)
    {
        if (empty($id)) return false;
        $data = Filebase::read_data('users', Filebase::hash($id), 'account');
        if (!$data) return false;
        $data['id'] = $id;
        self::$current_profile = $data;
        return $data;
    }

    static function SaveById($id)
    {
        if (empty(self::$current_profile)) return false;
        if (empty($id)) return false;
        Filebase::write_data('users', Filebase::hash($id), 'account', self::$current_profile);
        return true;
    }

    static function Create($basic_profile)
    {
        if (empty($basic_profile['email']) || empty($basic_profile['password'])) {
            return ['result' => false, 'message' => 'email_and_password_required'];
        }

        $email = strtolower(trim($basic_profile['email']));

        $existing = Filebase::read_data('users', Filebase::hash($email), 'account');
        if ($existing) return ['result' => false, 'message' => 'user_exists'];

        $now = time();
        $stored = $basic_profile;
        $stored['email'] = $email;
        $stored['password_hash'] = password_hash($basic_profile['password'], PASSWORD_DEFAULT);
        $stored['created'] = $now;
        if (!isset($stored['roles'])) $stored['roles'] = ['user'];

        Filebase::write_data('users', Filebase::hash($email), 'account', $stored);
        return ['result' => true, 'id' => $email, 'profile' => $stored];
    }

    static function AuthWithPassword($password, $basic_profile = false)
    {
        if ($basic_profile === false) {
            if (!isset($_POST['email'])) return ['result' => false, 'message' => 'email_missing'];
            $email = strtolower(trim($_POST['email']));
            $basic_profile = Filebase::read_data('users', Filebase::hash($email), 'account');
            if ($basic_profile) $basic_profile['id'] = $email;
        }

        if (!$basic_profile) return ['result' => false, 'message' => 'no_such_user'];
        if (!isset($basic_profile['password_hash'])) return ['result' => false, 'message' => 'no_password_set'];

        if (password_verify($password, $basic_profile['password_hash'])) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION[self::$session_key] = $basic_profile['id'];
            self::$current_profile = $basic_profile;
            return ['result' => true, 'profile' => $basic_profile];
        }

        return ['result' => false, 'message' => 'invalid_password'];
    }

    static function Permission($thing_name)
    {
        if (!self::IsSignedIn()) return false;
        $roles = self::$current_profile['roles'] ?? [];
        if (in_array('admin', $roles)) return true;

        $perm_map = [
            'edit' => ['admin', 'editor'],
            'view' => ['admin', 'editor', 'user'],
        ];

        $allowed = $perm_map[$thing_name] ?? ['admin'];
        return count(array_intersect($roles, $allowed)) > 0;
    }

    static function IsSignedIn()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (isset($_SESSION[self::$session_key]) && $_SESSION[self::$session_key]) {
            if (self::$current_profile) return true;
            $id = $_SESSION[self::$session_key];
            $profile = Filebase::read_data('users', Filebase::hash($id), 'account');
            if ($profile) {
                $profile['id'] = $id;
                self::$current_profile = $profile;
                return true;
            }
            unset($_SESSION[self::$session_key]);
        }
        return false;
    }

    static function Logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        unset($_SESSION[self::$session_key]);
        self::$current_profile = null;
        return true;
    }

}
