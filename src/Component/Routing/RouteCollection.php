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
      protected $controllers = [];




      /**
       * Collection route group
       *
       * @var RouteGroup[]
      */
      protected $groups = [];





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
       * @param RouteGroup $group
       * @return RouteGroup
      */
      public function addGroup(RouteGroup $group): RouteGroup
      {
           $this->groups[] = $group;

           $group->rewind();

           return $group;
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
       * Returns route groups
       *
       * @return RouteGroup[]
      */
      public function getGroups(): array
      {
           return $this->groups;
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