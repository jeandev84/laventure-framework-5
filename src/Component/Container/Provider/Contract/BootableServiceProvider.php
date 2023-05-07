<?php
namespace Laventure\Component\Container\Provider\Contract;

/**
 * @BootableServiceProvider
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Container\Provider\Contract
*/
interface BootableServiceProvider
{
    /**
     * Boot service provider
     *
     * @return void
    */
    public function boot(): void;
}