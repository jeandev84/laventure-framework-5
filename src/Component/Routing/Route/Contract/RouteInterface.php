<?php
namespace Laventure\Component\Routing\Route\Contract;


/**
 * @RouteInterface
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing\Route\Contract
*/
interface RouteInterface
{


     /**
      * Returns route domain
      *
      * @return string
     */
     public function getDomain(): string;



     /**
      * Returns route methods
      *
      * @return array
     */
     public function getMethods(): array;




     /**
      * Return route path
      *
      * @return string
     */
     public function getPath(): string;





     /**
      * Return route params
      *
      * @return array
     */
     public function getParams(): array;





     /**
      * Return route name
      *
      * @return string
     */
     public function getName(): string;







     /**
      * Returns route middlewares
      *
      * @return array
     */
     public function getMiddlewares(): array;






     /**
      * Return others route options
      *
      * @return array
     */
     public function getOptions(): array;




     /**
      * Determine if the route name is not empty
      *
      * @return bool
     */
     public function hasName(): bool;






     /**
      * Determine if route is callable
      *
      * @return bool
     */
     public function isCallable(): bool;





     /**
      * Return route callback if exist
      *
      * @return callable
     */
     public function getCallback(): callable;






     /**
      * Return controller name
      *
      * @return string
     */
     public function getControllerName(): string;




     /**
      * Return action name
      *
      * @return string
     */
     public function getActionName(): string;






     /**
      * Determine if the given request params match route
      *
      * @param string $requestMethod
      *
      * @param string $requestPath
      *
      * @return bool
     */
     public function match(string $requestMethod, string $requestPath): bool;





    /**
     * Generate route from given params
     *
     * @param array $params
     *
     * @return string
    */
    public function generatePath(array $params = []): string;
}