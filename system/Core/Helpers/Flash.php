<?php
    namespace MicroPos\Core\Helpers;

    use Plasticbrain\FlashMessages\FlashMessages;

    /**
     * Class Flash
     *
     * @package \MicroPos\Core\Helpers
     */
    class Flash extends FlashMessages
    {
        protected static $instance;

        public function __construct ()
        {
            static::$instance = $this;
        }

        /**
         * @return \MicroPos\Core\Helpers\Flash
         */
        public static function getInstance ()
        {
            return static::$instance;
        }

    }
