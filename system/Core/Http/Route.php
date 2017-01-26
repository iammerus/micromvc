<?php

	namespace MicroPos\Core\Http;

	use MicroPos\Core\View;
	use MicroPos\Core\Exception\MiddlewareExistsException;

	/**
	 * Represents a single route
	 *
	 * @package \MicroPos\Core\Http
	 */
	class Route
	{
		/**
		 * Path (Uri) for this route
		 * @var string
		 */
		protected $path;

		/**
		 * The action for this route
		 * @var object
		 */
		protected $action;

		/**
		 * The request method for this route
		 * @var string
		 */
		protected $method;

		/**
		 * Storage for this route's middleware
		 * @var array
		 */
		protected $middleware = [];

		/**
		 * The namespace for Middleware(s)
		 */
		const middlewareNamespace = "MicroPos\\Middleware\\";

		public function __construct(string $route , $method , $action = null)
		{
			$this->path = $route;
			$this->action = $action;
			$this->method = $method;
		}

		/**
		 * Returns the path for this route
		 * @return string
		 */
		public function getPath()
		{
			return $this->path;
		}

		/**
		 * Gets the request method for this route
		 * @return mixed
		 */
		public function getMethod()
		{
			return $this->method;
		}

		/**
		 * Attach a new Middleware to this route
		 * @param $middleware
		 * @throws MiddlewareExistsException
		 * @throws \InvalidMiddlewareException
		 */
		public function addMiddleware($middleware)
		{
			if (!is_null( $middleware ) && !empty($middleware)) {
				if (array_key_exists( $middleware , $this->middleware )) {
					throw new MiddlewareExistsException(
						"The middleware '{$middleware}' is already attached on the route '"
						. $this->path . "'"
					);
				}
				else {
					$this->middleware[] = $middleware;
				}
			}
			else {
				throw new \InvalidMiddlewareException;
			}
		}

		/**
		 * Executes the Middleware for this route
		 * @param $middleware string
		 * @return mixed
		 */
		public function executeMiddleware($middleware)
		{
			$instance = new $middleware;

			return $instance->handle( Request::getInstance() );
		}

		/**
		 * Runs the action for this route
		 * @return int
		 */
		public function dispatch()
		{
			if (!empty($this->middleware)) {
				foreach ($this->middleware as $m) {
					$this->executeMiddleware( $m );
				}
			}

			$action = new $this->action->class;

			$result = $action->{$this->action->method}(Request::getInstance());

			if ($result instanceof View) {
				return print $result->render();
			}

			return print $result;
		}
	}
