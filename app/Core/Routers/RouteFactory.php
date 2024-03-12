<?php

namespace App\Core\Routers;

use Illuminate\Routing\Router;

class RouteFactory
{
    /**
     * @var array
     */
    private $middleware;

    /**
     * @var Router
     */
    private mixed $api;

    /**
     * RouteFactory constructor.
     */
    public function __construct()
    {
        $this->api = app('Illuminate\Routing\Router');
    }

    /**
     * @return $this
     */
    public function setMiddleware(array $middleware): self
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * Register an array of RouterInterfaces on application.
     *
     * @return mixed
     */
    public function registerRoutes(array $routes)
    {
        if ($this->middleware) {
            return $this->makeMiddlewareRoute($routes);
        }

        $this->makeRoutes($routes);
    }

    /**
     * @return mixed
     */
    private function makeMiddlewareRoute(array $routes)
    {
        return $this->api->group(
            $this->middleware,
            function () use ($routes) {
                $this->makeRoutes($routes);
            }
        );
    }

    /**
     * Receives an array of Router classes and calls the routes method
     */
    private function makeRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            $route::routes($this->api);
        }
    }
}
