<?php
namespace Laventure\Component\Container\Provider\Contract;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\Contract\ContainerAwareInterface;

/**
 * @ServiceProviderInterface
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Container\Provider\Contract
*/
interface ServiceProviderInterface extends ContainerAwareInterface
{

    /**
     * Register service in container
     *
     * @return void
    */
    public function register(): void;
}