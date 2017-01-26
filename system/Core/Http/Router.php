<?php

	namespace MicroPos\Core\Http;

	use MicroPos\Core\App;
	use MicroPos\Core\Exception\NotFoundHttpException;
	use MicroPos\Core\Helpers\Url;

	/**
	 * This class handles routing in the application
	 *
	 * @package \MicroPos
	 */
	class Router
	{
		/**
		 * @var Route
		 */
		protected $matchedRoute;

		/**
		 * @var RouteCollection
		 */
		private $routes;


		/**
		 * Sets the application's routes
		 * @param \MicroPos\Core\Http\RouteCollection $collection
		 */
		public function setRoutes(RouteCollection $collection)
		{
			$this->routes = $collection;
		}


		/**
		 * Gets the action for the request route
		 *
		 * @throws NotFoundHttpException Throws 404 error if the requested page is not found
		 */
		public function match()
		{
			$uri = Url::detectUri();
			$request = Request::getInstance();

			$route = $this->routes->getRoute( $uri , strtolower( $request->getMethod() ) );

			if (is_null( $route )) {
				throw new NotFoundHttpException( "Page '" . $uri . "' was not found" );
			}
			else {
				$this->matchedRoute = $route;
			}
		}

		/**
		 * Executes the action for the matched route
		 *
		 * @return void
		 */
		public function dispatch()
		{
			if (!is_null( $this->matchedRoute )) {
				$this->matchedRoute->dispatch();
			}
			else {
                Response::addStatus(500);
				Response::sendHeaders();

				printInternalServerErrorPage();

				App::getInstance()->shutdown();
			}
		}

	}
