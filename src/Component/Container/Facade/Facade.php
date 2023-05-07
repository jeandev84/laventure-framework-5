<?php
namespace Laventure\Component\Container\Facade;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\Contract\ContainerInterface;
use Laventure\Component\Container\Facade\Exception\FacadeException;


/**
 * @Facade
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Container\Facade
*/
abstract class Facade
{

    /**
     * @var Container
     */
    protected static $container;



    /**
     * @var mixed
     */
    protected static $resolved;




    /**
     * Set container
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container)
    {
        static::$container = $container;
    }



    /**
     * Get instance of Facade
     *
     * dump($accessor, static::$container)
     * @return mixed
    */
    protected static function getFacadeInstance(): mixed
    {
        $accessor = static::getFacadeAccessor();

        if(! empty(static::$resolved[$accessor])) {
            return static::$resolved[$accessor];
        }

        return static::$resolved[$accessor] = static::$container->get($accessor);
    }



    /**
     * @param $method
     * @param $arguments
     * @return bool
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeInstance();

        if(! method_exists($instance, $method)) {
            return false;
        }

        return call_user_func_array([$instance, $method], $arguments);
    }



    /**
     * @return string
     */
    public function getName(): string
    {
        return get_called_class();
    }





    /**
     * Get name of facade to be resolve in container
     *
     * @return string
    */
    protected static function getFacadeAccessor(): string
    {
        return trigger_error("Facade assessor [". __METHOD__ . "] must be implemented.");
    }
}