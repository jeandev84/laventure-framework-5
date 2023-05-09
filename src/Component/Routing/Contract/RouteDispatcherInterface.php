<?php
namespace Laventure\Component\Routing\Contract;


use Laventure\Component\Routing\Route;

/**
 * @RouteDispatcherInterface
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing\Contract
*/
interface RouteDispatcherInterface
{

     /**
      * Dispatch route
      *
      * @param Route $route
      *
      * @return mixed
     */
     public function dispatchRoute(Route $route);
}