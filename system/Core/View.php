<?php

    namespace MicroPos\Core;

    use Philo\Blade\Blade;

    /**
     * Class View
     *
     * @package \MicroPos\Core
     */
    class View extends Blade
    {
        public function __construct (array $viewPaths, $cachePath)
        {
            parent::__construct($viewPaths, $cachePath);
        }
    }
