<?php
namespace Laventure\Component\Routing;


use Closure;

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
    protected $paths = [];




    /**
     * @var array
    */
    protected $modules  = [];




    /**
     * @var array
    */
    protected $names = [];




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

        $this->rewind();

        return $this;
    }






    /**
     * @param string $prefix
     * @return $this
    */
    private function path(string $prefix): static
    {
        $this->paths[] = trim($prefix, '\\/');

        return $this;
    }




    /**
     * @return string
    */
    public function getPath(): string
    {
        return join('/', $this->paths);
    }






    /**
     * @param string $module
     * @return $this
    */
    private function module(string $module): self
    {
        $this->modules[] = trim($module, '\\');

        return $this;
    }




    /**
     * @return string
    */
    public function getModule(): string
    {
       return join('\\', $this->modules);
    }




    /**
     * @param string $name
     * @return $this
    */
    private function name(string $name): self
    {
        $this->names[] = $name;

        return $this;
    }




    /**
     * @return string
    */
    public function getName(): string
    {
        return join($this->names);
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
    private function rewind(): void
    {
        $this->paths   = [];
        $this->modules = [];
        $this->names   = [];
        $this->middlewares = [];
    }
}