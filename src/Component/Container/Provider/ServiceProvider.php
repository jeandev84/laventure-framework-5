<?php
namespace Laventure\Component\Container\Provider;

use Laventure\Component\Container\Container;
use Laventure\Component\Container\Provider\Contract\ServiceProviderInterface;

/**
 * @ServiceProvider
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Container\Provider
*/
abstract class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @var Container
    */
    protected $app;




    /**
     * @var array
    */
    protected $provides = [];




    /**
     * Returns name of called provider
     *
     * @return string
    */
    public function getName(): string
    {
        return get_called_class();
    }




    /**
     * @param Container $app
    */
    public function setContainer(Container $app): void
    {
         $this->app = $app;
    }





    /**
     * @return Container
    */
    public function getContainer(): Container
    {
        return $this->app;
    }




    /**
     * @return array
    */
    public function getProvides(): array
    {
        return $this->provides;
    }



    /**
     * @return void
    */
    public function terminate() {}
}