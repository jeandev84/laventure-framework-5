<?php
namespace Laventure\Component\Routing;


use Closure;

/**
 * @Route
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing
*/
class Route
{



    /**
     * Route domain
     *
     * @var  string
    */
    protected $domain;





    /**
     * Route methods
     *
     * @var array
    */
    protected $methods = [];





    /**
     * Route path
     *
     * @var string
    */
    protected $path;




    /**
     * Route pattern
     *
     * @var string
    */
    protected $pattern;




    /**
     * Route callback handle.
     *
     * @var callable
    */
    protected $callback;





    /**
     * Route controller and action
     *
     * @var array
    */
    protected $action = [
        "controller" => "",
        "action"     => ""
    ];





    /**
     * Route name
     *
     * @var string
    */
    protected $name;




    /**
     * Route params
     *
     * @var array
    */
    protected $params = [];




    /**
     * Route middlewares
     *
     * @var array
    */
    protected $middlewares = [];




    /**
     * Route patterns
     *
     * @var array
    */
    protected $patterns = [];





    /**
     * Route options
     *
     * @var array
    */
    protected $options = [];





    /**
     * Route constructor
     *
     * @param $methods
     *
     * @param $path
    */
    public function __construct($methods, $path)
    {
         $this->methods($methods);
         $this->path($path);
    }





    /**
     * Set methods
     *
     * @param array|string $methods
     *
     * @return $this
     */
    public function methods(string|array $methods): static
    {
        if (is_string($methods)) {
            $methods = explode('|', $methods);
        }

        $this->methods = $methods;

        return $this;
    }




    /**
     * Set route path
     *
     * @param string $path
     *
     * @return $this
    */
    public function path(string $path): static
    {
        $this->path = $path ?: '/';

        $this->pattern($path);

        return $this;
    }




    /**
     * @param string $pattern
     *
     * @return $this
    */
    public function pattern(string $pattern): static
    {
         $this->pattern = '/'. trim($pattern, '\\/');

         return $this;
    }





    /**
     * Add middlewares
     *
     * @param $middlewares
     *
     * @return $this
    */
    public function middleware($middlewares): static
    {
         $this->middlewares = array_merge($this->middlewares, (array) $middlewares);

         return $this;
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
     * Set route callback
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function callback(callable $callback): static
    {
        $this->callback = $callback;

        return $this;
    }




    /**
     * Set route controller
     *
     * @param string $controller
     *
     * @param string $action
     *
     * @return $this
     */
    public function controller(string $controller, string $action): static
    {
        $action = compact('controller', 'action');

        $this->action = array_merge($this->action, $action);

        return $this;
    }




    /**
     * Set route name
     *
     * @param string $name
     *
     * @return static
    */
    public function name(string $name): static
    {
        $this->name .= $name;

        return $this;
    }




    /**
     * Route options
     *
     * @param array $options
     *
     * @return $this
    */
    public function options(array $options): static
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }





    /**
     * Set route patterns
     *
     * @param array $patterns
     *
     * @return $this
    */
    public function wheres(array $patterns): static
    {
        foreach ($patterns as $name => $pattern) {
            $this->where($name, $pattern);
        }

        return $this;
    }




    /**
     * @param string $name
     * @param string $pattern
     * @return $this
    */
    public function where(string $name, string $pattern): static
    {
         $this->patterns[$name] = $pattern;

         $this->pattern($this->replacePlaceholders($name, $pattern));

         return $this;
    }




    /**
     * @param string $name
     * @return $this
    */
    public function whereNumber(string $name): self
    {
        return $this->where($name, '\d+');
    }




    /**
     * @param string $name
     * @return $this
    */
    public function whereText(string $name): self
    {
        return $this->where($name, '\w+');
    }




    /**
     * @param string $name
     * @return $this
    */
    public function whereAlphaNumeric(string $name): self
    {
        return $this->where($name, '[^a-z_\-0-9]'); // [^a-z_\-0-9]
    }





    /**
     * @param string $name
     * @return $this
    */
    public function whereSlug(string $name): self
    {
        return $this->where($name, '[a-z\-0-9]+');
    }




    /**
     * @param string $name
     * @return $this
    */
    public function anything(string $name): self
    {
        return $this->where($name, '.*');
    }




    /**
     * Return controller name
     *
     * @return string
    */
    public function getController(): string
    {
         return $this->action['controller'];
    }




    /**
     * Return action name
     *
     * @return string
    */
    public function getAction(): string
    {
        return $this->action["action"];
    }





    /**
     * Return route name
     *
     * @return string
    */
    public function getName(): string
    {
        return $this->name;
    }






    /**
     * Returns route domain
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }




    /**
     * Returns route methods
     *
     * @return array
    */
    public function getMethods(): array
    {
         return $this->methods;
    }




    /**
     * Return route methods as string
     *
     * @return string
    */
    public function getMethodsAsString(): string
    {
        return join("|", $this->methods);
    }





    /**
     * Returns route requirements
     *
     * @return array
    */
    public function getPatterns(): array
    {
        return $this->patterns;
    }





    /**
     * Return route callback if exist
     *
     * @return callable
    */
    public function getCallback(): callable
    {
        return $this->callback;
    }





    /**
     * Return others route options
     *
     * @return array
    */
    public function getOptions(): array
    {
        return $this->options;
    }




    /**
     * Determine if the route name is not empty
     *
     * @return bool
    */
    public function hasName(): bool
    {
        return ! empty($this->name);
    }




    /**
     * Determine if route is callable
     *
     * @return bool
    */
    public function isCallable(): bool
    {
        return is_callable($this->callback);
    }





    /**
     * Return route path
     *
     * @return string
    */
    public function getPath(): string
    {
        return '/'. trim($this->path, '\\/');
    }





    /**
     * Return route params
     *
     * @return array
    */
    public function getParams(): array
    {
         return $this->params;
    }




    /**
     * Returns route middlewares
     *
     * @return array
    */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }





    /**
     * Determine if the given request params match route
     *
     * @param string $method
     *
     * @param string $path
     *
     * @return bool
    */
    public function match(string $method, string $path): bool
    {
         return $this->matchMethod($method) && $this->matchPath($path);
    }




    /**
     * Generate route from given params
     *
     * @param array $params
     *
     * @return string
    */
    public function generatePath(array $params = []): string {}




    /**
     * Call Closure
     *
     * @return mixed
    */
    public function callAnonymous(): mixed
    {
        if (! $this->isCallable()) {
             return false;
        }

        return $this->call($this->callback);
    }



    /**
     * Call controller and action
     *
     * @return mixed
    */
    public function callAction(): mixed
    {
         $controller = $this->getController();
         $action     = $this->getAction();

         if (! method_exists($controller, $action)) {
              return false;
         }
         
         return $this->call([new $controller, $action]);
    }




    /**
     * @return bool
    */
    public function hasController(): bool
    {
        return ! empty($this->action["controller"]);
    }




    /**
     * Return route pattern
     *
     * @return string
    */
    public function getPattern(): string
    {
         return $this->pattern;
    }





    /**
     * Determine if the given method match route methods
     *
     * @param string $requestMethod
     *
     * @return bool
    */
    public function matchMethod(string $requestMethod): bool
    {
         return in_array($requestMethod, $this->methods);
    }




    /**
     * Determine if the give path match route path
     *
     * @param string $path
     *
     * @return bool
    */
    public function matchPath(string $path): bool
    {
         if (preg_match("#^{$this->getPattern()}$#i", $this->resolveURL($path), $matches)) {
              $this->params = $this->resolveParams($matches);
              return true;
         }

         return false;
    }





    /**
     * @param string $path
     *
     * @return string
    */
    private function resolveURL(string $path): string
    {
         return '/'. parse_url(trim($path, '\\/'), PHP_URL_PATH);
    }






    /**
     * @param string $name
     *
     * @param string $regex
     *
     * @return string
    */
    private function replacePlaceholders(string $name, string $regex): string
    {
         $regex        = str_replace('(', '(?:', $regex);
         $regex        = sprintf('(?P<%s>%s)', $name, $regex);
         $placeholders = ["#{{$name}}#", "#{{$name}.?}#"];
         $patterns     = [$regex, '?'. $regex .'?'];

         return preg_replace($placeholders, $patterns, $this->pattern);
    }



    /**
     * @param array $matches
     * @return array
    */
    private function resolveParams(array $matches): array
    {
        return array_filter($matches, function ($key) {

            return ! is_numeric($key);

        }, ARRAY_FILTER_USE_KEY);
    }



    /**
     * @param callable $callable
     *
     * @return mixed
    */
    public function call(callable $callable): mixed
    {
         return call_user_func_array($callable, array_values($this->getParams()));
    }
}