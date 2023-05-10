<?php
namespace Laventure\Component\Routing\Route;


use Closure;
use Laventure\Component\Routing\Router;

/**
 * @RouteGroup
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing\Route
*/
class RouteGroup
{
    /**
     * @var array
    */
    protected $path = [];




    /**
     * @var array
    */
    protected $module  = [];




    /**
     * @var array
    */
    protected $name = [];




    /**
     * @var array
    */
    protected $middlewares = [];




    /**
     * @param array $prefixes
     *
     * @return $this
    */
    public function prefixes(array $prefixes): static
    {
        foreach ($prefixes as $name => $value) {
            if (property_exists($this, $name)) {
                call_user_func([$this, $name], $value);
            }
        }

        return $this;
    }




    /**
     * @param Closure $routes
     *
     * @param Router $router
     *
     * @return static
    */
    public function map(Closure $routes, Router $router): static
    {
        call_user_func($routes, $router);

        return $this;
    }






    /**
     * @param string $prefix
     * @return $this
    */
    private function path(string $prefix): static
    {
        $this->path[] = trim($prefix, '\\/');

        return $this;
    }




    /**
     * @return string
    */
    public function getPath(): string
    {
        return join('/', $this->path);
    }






    /**
     * @param string $module
     * @return $this
    */
    private function module(string $module): self
    {
        $this->module[] = trim($module, '\\');

        return $this;
    }




    /**
     * @return string
    */
    public function getModule(): string
    {
       return join('\\', $this->module);
    }




    /**
     * @param string $name
     * @return $this
    */
    private function name(string $name): self
    {
        $this->name[] = $name;

        return $this;
    }




    /**
     * @return string
    */
    public function getName(): string
    {
        return join($this->name);
    }





    /**
     * @param array $middlewares
     * @return $this
    */
    private function middlewares(array $middlewares): self
    {
        $this->middlewares = array_merge($this->middlewares, $middlewares);

        return $this;
    }




    /**
     * @return array
    */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }





    /**
     * @return void
    */
    public function rewind(): void
    {
        $this->path   = [];
        $this->module = [];
        $this->name   = [];
        $this->middlewares = [];
    }
}