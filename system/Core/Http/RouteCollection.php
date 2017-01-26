<?php

namespace MicroPos\Core\Http;

use MicroPos\Core\Exception\ControllerNotFoundException;
use MicroPos\Core\Exception\FileNotFoundException;
use MicroPos\Core\Exception\InvalidControllerException;
use MicroPos\Core\Exception\InvalidMiddlewareException;
use MicroPos\Core\Exception\MethodNotFound;
use MicroPos\Core\Exception\MethodNotFoundException;
use MicroPos\Core\Exception\RouteExistsException;


/**s
 * Used to store application's routes
 *
 * @package \MicroPos\Core\Http
 */
class RouteCollection
{

    /**
     * @var array
     */
    protected $collection = [];

    protected $controllerNamespace = "\\MicroPos\\Controllers\\";

    protected $middlewareNamespace = "\\MicroPos\\Middleware\\";

    public function __construct()
    {

    }

    /**
     * Adds a route for the GET Http method
     * @param        $route
     * @param        $controller
     * @param string|mixed|null $middleware
     * @throws \MicroPos\Core\Exception\ControllerNotFoundException
     * @throws \MicroPos\Core\Exception\InvalidControllerException
     * @throws \MicroPos\Core\Exception\RouteExistsException
     * @throws \MicroPos\Core\Exception\FileNotFoundException
     * @throws \MicroPos\Core\Exception\InvalidMiddlewareException
     */
    public function get($route, $controller, $middleware = null)
    {
        $data = $this->parseClass($controller);

        if (!class_exists($data->class)) {
            $ex = new ControllerNotFoundException;

            $ex->setMessage("The controller '".$data->class."' was not found in ".__FILE__." on line ".__LINE__);
            $ex->setController($data->class);

            throw $ex;
        }

        if (!method_exists($data->class, $data->method)) {
            throw new MethodNotFoundException("The method '{$data->method}' was not found in '{$data->class}'");
        }

        $route = new Route($route, "get", $data);


        if (!is_null($middleware)) {

            if (!is_array($middleware)) {
                $middleware = [$middleware];
            }

            foreach ($middleware as $m) {

                $m = $this->getFullMiddleware($m);

                if (class_exists($m)) {

                    $implements = class_implements($m);

                    if (!array_key_exists("MicroPos\\Http\\Middleware", $implements)) {
                        throw new InvalidMiddlewareException(
                            "'{$m}' is not a valid middleware"
                            .". All middleware's must implement 'MicroPos\\Http\\Middleware'"
                        );
                    } else {
                        $route->addMiddleware($m);
                    }
                } else {
                    throw new FileNotFoundException("The middleware '{$m}' was not found.'");
                }
            }
        }

        return $this->attach($route);
    }

    /**
     * Split a route's action string
     * @param string $class
     * @return object
     * @throws InvalidControllerException
     */
    protected function parseClass($class)
    {
        if (is_null($class) || empty($class)) {
            throw new \InvalidArgumentException();
        }

        $class = explode("::", $class);

        if (count($class) != 2) {
            throw new InvalidControllerException("Invalid controller string on route entry");
        }

        return (object)[
            'class' => $this->controllerNamespace.$class[0],
            'method' => $class[1],
        ];
    }

    /**
     * Prepend middleware namespace to the given middleware class string
     * @var $name string The middleware class name
     * @returns string
     */
    protected function getFullMiddleware($name)
    {
        return $this->middlewareNamespace.$name;
    }

    /**
     * Adds a new Route to the collection
     * @param \MicroPos\Core\Http\Route $route
     * @throws RouteExistsException
     */
    protected function attach(Route $route)
    {
        if ($this->routeExists($route->getPath(), $route->getMethod()) === true) {
            throw new RouteExistsException("The route '".$route->getPath()." already exists in route collection");
        }

        $this->collection[] = $route;
    }

    /**
     * Checks if the route exists in collection
     * @param string $path
     * @param string $method
     * @returns bool
     *
     * TODO: Update function to search more efficiently
     */
    public function routeExists($path, $method)
    {
        foreach ($this->collection as $route) {
            if ($route->getPath() == $path && $route->getMethod() == $method) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a route that matches the specified path and method
     * @param $path
     * @param $method
     * @return null|Route
     */
    public function getRoute($path, $method)
    {
        foreach ($this->collection as $route) {
            if ($route->getPath() == $path && $route->getMethod() == $method) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Get route collection as an array
     * @return array
     */
    public function getRoutes()
    {
        return $this->collection;
    }

    /**
     * Checks if the specified Route is in the Route collection
     * @param Route $route
     * @return bool
     */
    public function has(Route $route)
    {
        return $this->contains($route);
    }


    /**
     * Adds a route for the POST Http method
     * @param        $route
     * @param        $controller
     * @param string|mixed|null $middleware
     * @throws \MicroPos\Core\Exception\ControllerNotFoundException
     * @throws \MicroPos\Core\Exception\InvalidControllerException
     * @throws \MicroPos\Core\Exception\RouteExistsException
     * @throws \MicroPos\Core\Exception\FileNotFoundException
     * @throws \MicroPos\Core\Exception\InvalidMiddlewareException
     */
    public function post($route, $controller, $middleware = null)
    {
        $data = $this->parseClass($controller);

        if (!class_exists($data->class)) {
            $ex = new ControllerNotFoundException;

            $ex->setMessage("The controller '".$data->class."' was not found in ".__FILE__." on line ".__LINE__);
            $ex->setController($data->class);

            throw $ex;
        }

        if (!method_exists($data->class, $data->method)) {
            throw new MethodNotFoundException("The method '{$data->method}' was not found in '{$data->class}'");
        }

        $route = new Route($route, "post", $data);

        if (!is_null($middleware) && !empty($middleware)) {

            if (!is_array($middleware)) {
                $middleware = [$middleware];
            }

            foreach ($middleware as $m) {
                $m = $this->getFullMiddleware($m);

                if (class_exists($m)) {

                    $implements = class_implements($m);

                    if (!array_key_exists("MicroPos\\Core\\Http\\Middleware", $implements)) {
                        throw new InvalidMiddlewareException(
                            "'{$m}' is not a valid middleware"
                            .". All middleware's must implement 'MicroPos\\Core\\Http\\Middleware'"
                        );
                    } else {
                        $route->addMiddleware($m);
                    }
                } else {
                    throw new FileNotFoundException("The middleware '{$m}' was not found.'");
                }
            }
        }

        return $this->attach($route);
    }
}
