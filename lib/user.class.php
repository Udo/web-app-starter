<?php

    class User
    {

        public $id;
        public $basic_profile;

        static function LoadById($id)
        {
            self::$basic_profile = [];
            // insert your loading code here
            return self::$basic_profile;
        }

        static function SaveById($id)
        {
            // insert your saving code here
            return true;
        }

        static function Create($basic_profile)
        {
            // insert your creation code here
            return ['result' => false, 'message' => 'not implemented'];
        }

        static function AuthWithPassword($password, $basic_profile = false)
        {
            if(!$basic_profile) $basic_profile = self::$basic_profile;
            // insert your authentication code here
            return ['result' => false, 'message' => 'not implemented'];
        }

        static function Permission($thing_name)
        {
            // insert your permission checking code here
            return false;
        }

        static function IsSignedIn()
        {
            // insert your signed-in checking code here
            return false;
        }

    }