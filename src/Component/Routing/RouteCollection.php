<?php
namespace Laventure\Component\Routing;


/**
 * @RouteCollection
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing
*/
class RouteCollection
{

      /**
       * Collection routes
       *
       * @var Route[]
      */
      protected $routes = [];




      /**
       * Collection named routes
       *
       * @var Route[]
      */
      protected $names = [];





      /**
       * Collection routes methods
       *
       * @var Route[]
      */
      protected $methods = [];






      /**
       * Collection routes controllers
       *
       * @var array
      */
      public $controllers = [];





      /**
       * Add route
       *
       * @param Route $route
       *
       * @return Route
      */
      public function addRoute(Route $route): Route
      {
           $methods = $route->getMethodsAsString();
           $path    = $route->getPath();

           $this->methods[$methods][$path] = $route;

           if ($route->hasController()) {
               $this->controllers[$route->getController()][$path] = $route;
           }

           if ($route->hasName()) {
              $this->names[$route->getName()] = $route;
           }

           $this->routes[$path] = $route;

           return $route;
      }



     /**
      * Add named route
      *
      * @param string $name
      *
      * @param Route $route
      *
      * @return Route
     */
      public function add(string $name, Route $route): Route
      {
          $route->name($name);

          return $this->addRoute($route);
      }




      /**
       * Return named routes
       *
       * @return Route[]
      */
      public function getNamedRoutes(): array
      {
           foreach ($this->routes as $route) {
               if ($route->hasName() && ! $this->hasName($route->getName())) {
                   $this->names[$route->getName()] = $route;
               }
           }

           return $this->names;
      }




      /**
       * Returns routes by method
       *
       * @return Route[]
      */
      public function getRoutesByMethod(): array
      {
           return $this->methods;
      }




      /**
       * Returns routes by controller
       *
       * @return Route[]
      */
      public function getRoutesByController(): array
      {
          return $this->controllers;
      }





      /**
       * Returns all routes
       *
       * @return Route[]
      */
      public function getRoutes(): array
      {
           return $this->routes;
      }




      /**
       * Determine if the given name is in named routes
       *
       * @param string $name
       *
       * @return bool
      */
      public function hasName(string $name): bool
      {
          return isset($this->getNamedRoutes()[$name]);
      }




      /**
       * Return named route by given name
       *
       * @param string $name
       *
       * @return Route|null
      */
      public function getNamedRoute(string $name): ?Route
      {
            return $this->getNamedRoutes()[$name] ?? null;
      }
}