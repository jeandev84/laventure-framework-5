<?php
namespace Laventure\Component\Routing\Contract;


/**
 * @RouterInterface
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing\Contract
*/
interface RouterInterface
{

    /**
     * Determine if the current request method and path match route.
     *
     * @param string $requestMethod
     *
     * @param string $requestPath
     *
     * @return mixed
    */
    public function match(string $requestMethod, string $requestPath);




    /**
     * Get route collection
     *
     * @return mixed
    */
    public function getRoutes();



    /**
     * Generate URI by given name
     *
     * @param string $name
     *
     * @param array $parameters
     *
     * @return mixed
    */
    public function generate(string $name, array $parameters = []);
}