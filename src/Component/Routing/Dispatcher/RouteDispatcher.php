<?php
namespace Laventure\Component\Routing\Dispatcher;

use Laventure\Component\Routing\Route\Route;

/**
 * @RouteDispatcher
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing
*/
class RouteDispatcher implements RouteDispatcherInterface
{

    /**
     * @inheritDoc
    */
    public function dispatchRoute(Route $route)
    {
         if ($route->isCallable()) {
              return $route->callAnonymous();
         }

         return $route->callAction();
    }
}