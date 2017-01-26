<?php
	/*
	 * Create an instance of the route collection object
	 *
	 * Do not remove this line
	 */
	$route = new \MicroPos\Core\Http\RouteCollection();

    $route->get('/', 'DemoController::home');

	/*
	 * Return the route collection object
	 *
	 * Do not remove this line
	 */
	return $route;
