<?php

    namespace MicroPos\Core\Http;

    /**
     * Class Request
     *
     * @package \MicroPos\Core\Http
     */
    class Request
    {
        protected static $instance = null;

        protected $requestData;

        /**
         * Safer and better access to $_FILES.
         *
         * @param  string $key
         * @static static method
         *
         * @return mixed
         */
        public function files ($key)
        {
            return array_key_exists($key, $_FILES) ? $_FILES[$key] : null;
        }


        /**
         * Request constructor.
         */
        public function __construct ()
        {
            $this->requestData = array_merge($_POST, $_GET);
        }

        public static function getInstance ()
        {
            if (is_null(static::$instance)) {
                return static::capture();
            }

            return static::$instance;
        }

        public static function capture ()
        {
            if (is_null(static::$instance)) {
                return (static::$instance = new Request());
            }
        }

        /**
         * Gets the request method.
         *
         * @return string
         */
        public function getMethod ()
        {
            $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

            if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
            } elseif (isset($_REQUEST['_method'])) {
                $method = $_REQUEST['_method'];
            }

            return strtoupper($method);
        }

        /**
         * Detect if request is Ajax.
         *
         * @static static method
         *
         * @return boolean
         */
        public function isAjax ()
        {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            }

            return false;
        }

        public function has($key)
        {
            return array_key_exists($key, $this->requestData);
        }

        /**
         * Detect if request is GET request.
         *
         * @static static method
         *
         * @return boolean
         */
        public function isGet ()
        {
            return $_SERVER["REQUEST_METHOD"] === "GET";
        }

        /**
         * Detect if request is POST request.
         *
         * @static static method
         *
         * @return boolean
         */
        public function isPost ()
        {
            return $_SERVER["REQUEST_METHOD"] === "POST";
        }

        /**
         * Safer and better access to $_POST.
         *
         * @param  string $key
         * @static static method
         *
         * @return mixed
         */
        public function post ($key = null)
        {
            if(is_null($key)) {
                return $_POST;
            }

            return array_key_exists($key, $_POST) ? $_POST[$key] : null;
        }

        /**
         * Safer and better access to $_GET.
         *
         * @param  string $key
         * @static static method
         *
         * @return mixed
         */
        public function query ($key)
        {
            return self::get($key);
        }

        /**
         * Safer and better access to $_GET.
         *
         * @param  string $key
         * @static static method
         *
         * @return mixed
         */
        public function get ($key)
        {
            return array_key_exists($key, $_GET) ? $_GET[$key] : null;
        }
    }
