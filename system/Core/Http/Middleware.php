<?php

    namespace MicroPos\Core\Http;

    /**
     * Base interface for all middleware
     *
     * @package \MicroPos\Core\Http
     */
    interface Middleware
    {
        /**
         * @param Request $r Instance of the Request class
         * @return mixed
         */
        public function handle(Request $r);
    }
