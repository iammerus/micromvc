<?php

    namespace MicroPos\Core\Exception;

    /**
     * Class ControllerNotFoundException
     *
     * @package \MicroPos\Core\Exception
     */
    class ControllerNotFoundException extends FileNotFoundException
    {
        protected $controller;

        public function setController(string $controller) {
            $this->controller = $controller;
        }

        public function getController()
        {
            return $this->controller;
        }

        public function setMessage($message)
        {
            $this->message = $message;
        }
    }
