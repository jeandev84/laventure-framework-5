<?php
namespace Laventure\Component\Routing;

use Closure;
use Laventure\Component\Routing\Contract\RouteDispatcherInterface;
use Laventure\Component\Routing\Contract\RouterInterface;
use Laventure\Component\Routing\Exception\RouteNotFoundException;
use Laventure\Component\Routing\Route\RouteGroup;


/**
 * @Router
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing
*/
class Router implements RouterInterface
{


    /**
     * Route Factory
     *
     * @var RouteFactory
    */
    private $factory;



    /**
     * Route collection
     *
     * @var RouteCollection
    */
    protected $collection;




    /**
     * Route parameter
     *
     * @var RouteParameter
    */
    protected $parameter;




    /**
     * Route dispatcher
     *
     * @var RouteDispatcherInterface
    */
    protected $dispatcher;




    /**
     * Route domain
     *
     * @var string
    */
    protected $domain;




    /**
     * Controller namespace
     *
     * @var string
    */
    protected $namespace;




    /**
     * Current route group
     *
     * @var RouteGroup
    */
    protected $routeGroup;





    /**
     * route patterns
     *
     * @var array
    */
    protected $patterns = [];





    /**
     * Router constructor
     *
     * @param RouteDispatcherInterface|null $dispatcher
    */
    public function __construct(RouteDispatcherInterface $dispatcher = null)
    {
        $this->factory    = new RouteFactory();
        $this->collection = new RouteCollection();
        $this->routeGroup = new RouteGroup();
        $this->dispatcher = $dispatcher ?: new RouteDispatcher();
    }




    /**
     * Set domain
     *
     * @param string $domain
     *
     * @return $this
    */
    public function domain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }




    /**
     * Set controller namespace
     *
     * @param string $namespace
     *
     * @return $this
    */
    public function namespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }




    /**
     * @return string
    */
    public function getNamespace(): string
    {
        return $this->namespace;
    }





    /**
     * Set route patterns
     *
     * @param array $patterns
     *
     * @return $this
    */
    public function patterns(array $patterns): static
    {
        $this->patterns = array_merge($this->patterns, $patterns);

        return $this;
    }



    /**
     * Set route pattern
     *
     * @param string $name
     *
     * @param $pattern
     *
     * @return $this
    */
    public function pattern(string $name, $pattern): static
    {
         return $this->patterns([$name => $pattern]);
    }


    /**
     * @param string $methods
     *
     * @param string $path
     *
     * @param Closure|array|string $handler
     *
     * @return Route
    */
    public function makeRoute(string $methods, string $path, $handler): Route
    {
        $route = $this->factory->createRoute($methods, $path);

        $route->domain($this->domain);
        $route->wheres($this->patterns);

        return $this->processHandler($route, $handler);
    }





    /**
     * @param Route $route
     *
     * @return Route
    */
    public function addRoute(Route $route): Route
    {
         return $this->collection->addRoute($route);
    }




    /**
     * Map routes
     *
     * @param string $methods
     *
     * @param string $path
     *
     * @param Closure|array|string $handler
     *
     * @return Route
    */
    public function map(string $methods, string $path, $handler): Route
    {
         return $this->addRoute($this->makeRoute($methods, $path, $handler));
    }





    /**
     * Map route called by method GET
     *
     * @param $path
     *
     * @param $handler
     *
     * @return Route
    */
    public function get($path, $handler): Route
    {
         return $this->map('GET', $path, $handler);
    }





    /**
     * Map route called by method POST
     *
     * @param $path
     *
     * @param $handler
     *
     * @return Route
    */
    public function post($path, $handler): Route
    {
         return $this->map('POST', $path, $handler);
    }




    /**
     * Map route called by method PUT
     *
     * @param $path
     *
     * @param $handler
     *
     * @return Route
    */
    public function put($path, $handler): Route
    {
         return $this->map('PUT', $path, $handler);
    }



    /**
     * @param $path
     * @param $handler
     * @return Route
    */
    public function delete($path, $handler): Route
    {
        return $this->map('DELETE', $path, $handler);
    }





    /**
     * @inheritDoc
    */
    public function match(string $requestMethod, string $requestPath)
    {
        foreach ($this->getRoutes() as $route) {
             if ($route->match($requestMethod, $requestPath)) {
                 return $route;
             }
        }

        return false;
    }





    /**
     * Dispatch matched route
     *
     * @param string $requestMethod
     *
     * @param string $requestPath
     *
     * @return mixed
     *
     * @throws RouteNotFoundException
    */
    public function dispatchRoute(string $requestMethod, string $requestPath): mixed
    {
          if (! $route = $this->match($requestMethod, $requestPath)) {
                throw new RouteNotFoundException("Route $requestPath not found.");
          }

          return $this->dispatcher->dispatchRoute($route);
    }




    /**
     * @inheritDoc
    */
    public function getRoutes(): array
    {
        return $this->collection->getRoutes();
    }





    /**
     * Route collection
     *
     * @return RouteCollection
    */
    public function getCollection(): RouteCollection
    {
         return $this->collection;
    }





    /**
     * @inheritDoc
    */
    public function generate(string $name, array $parameters = [])
    {
         return $this->collection->getNamedRoute($name)?->generatePath($parameters);
    }





    /**
     * @param Route $route
     *
     * @param mixed $handler
     *
     * @return Route
    */
    private function processHandler(Route $route, $handler): Route
    {
        if (is_array($handler)) {
            $route->controller($handler[0], $handler[1] ?? '__invoke');
        } elseif ($handler instanceof Closure) {
            $route->callback($handler);
        } elseif (is_string($handler)) {
            list($controller, $action) = explode('@', $handler, 2);
            $route->controller($controller, $action);
        }

        return $route;
    }
}