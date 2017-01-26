<?php

    namespace MicroPos\Core\Helpers;

    /**
     * Class Csrf
     *
     * @package \MicroPos\Core\Helpers
     */
    class Csrf
    {

        /**
         * checks if CSRF token in session is same as in the form submitted
         *
         * @access public
         * @static static method
         * @return bool
         */
        public static function isTokenValid ()
        {
            return $_POST["__token"] === Session::get("__token");
        }

        /**
         * get CSRF token and generate a new one if expired
         *
         * @access public
         * @static static method
         * @return string
         */
        public static function makeToken ()
        {
            $max_time = 60 * 60 * 24; // token is valid for 1 day
            $csrf_token = Session::get("__token");
            $stored_time = Session::get("__token" . '_time');

            if ($max_time + $stored_time <= time() || empty($csrf_token)) {
                Session::set("__token", md5(uniqid(rand(), true)));
                Session::set("__token" . '_time', time());
            }

            return Session::get("__token");
        }
    }
