<?php
namespace Laventure\Component\Routing;


/**
 * @RouteFactory
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing\Route
*/
class RouteFactory
{


     /**
      * @param $methods
      *
      * @param $path
      *
      * @return Route
     */
     public function createRoute($methods, $path): Route
     {
          return new Route($methods, $path);
     }
}