<?php

namespace Laventure\Component\Container\Contract;

use Laventure\Component\Container\Container;

/**
 * @ContainerAwareInterface
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Container\Contract
*/
interface ContainerAwareInterface
{
    /**
     * Set instance of container
     *
     * @param Container $container
     *
     * @return void
    */
    public function setContainer(Container $container): void;




    /**
     * Return instance of container
     *
     * @return Container
    */
    public function getContainer(): Container;
}
