<?php
namespace Laventure\Component\Routing\Route;

use Laventure\Component\Routing\Route\Contract\RouteInterface;


/**
 * @Route
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package Laventure\Component\Routing\Route
*/
class Route implements RouteInterface
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
     * Set methods
     *
     * @param array $methods
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
        $path = $path ?: '/';
        $this->path    = $path;
        $this->pattern = $path;

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
     * @param string $name
     * @param string $pattern
     * @return $this
    */
    public function where(string $name, string $pattern): static
    {
         $this->patterns[$name] = $pattern;

         $this->pattern = $this->resolvePattern($name, $pattern);

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
     * @return string
    */
    public function getControllerName(): string
    {
         return $this->action['controller'];
    }




    /**
     * Return controller action
     *
     * @return string
    */
    public function getActionName(): string
    {
        return $this->action["action"];
    }




    /**
     * @inheritDoc
    */
    public function getAction(): array
    {
         return $this->action;
    }






    /**
     * @inheritDoc
    */
    public function getName(): string
    {
        return $this->name;
    }




    /**
     * @inheritDoc
    */
    public function getDomain(): string
    {
        return $this->domain;
    }




    /**
     * @inheritDoc
    */
    public function getMethods(): array
    {
        return $this->methods;
    }




    /**
     * Convert methods to string
     *
     * @return string
    */
    public function getMethodsAsString(): string
    {
        return join("|", $this->methods);
    }




    /**
     * @inheritDoc
    */
    public function getCallback(): callable
    {
        return $this->callback;
    }





    /**
     * @inheritDoc
    */
    public function getOptions(): array
    {
        return $this->options;
    }






    /**
     * @return bool
    */
    public function isCallable(): bool
    {
        return is_callable($this->callback);
    }





    /**
     * @inheritDoc
    */
    public function getPath(): string
    {
        return $this->path;
    }





    /**
     * @inheritDoc
    */
    public function getParams(): array
    {
         return $this->params;
    }




    /**
     * @inheritDoc
    */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }





    /**
     * @inheritDoc
    */
    public function match(string $requestMethod, string $requestPath): bool
    {
         return $this->matchRequestMethod($requestMethod) && $this->matchRequestPath($requestPath);
    }




    /**
     * @inheritDoc
    */
    public function generate(array $params = []): string
    {

    }




    /**
     * Call Closure
     *
     * @return mixed
    */
    public function callAnonymous(): mixed
    {
        if (! is_callable($this->callback)) {
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
         $controller = $this->getControllerName();
         $action     = $this->getActionName();

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
         return "#^{$this->pattern}$#i";
    }





    /**
     * Determine if the given method match route methods
     *
     * @param string $requestMethod
     *
     * @return bool
    */
    public function matchRequestMethod(string $requestMethod): bool
    {
         if(in_array($requestMethod, $this->methods)) {
              $this->options(compact('requestMethod'));
              return true;
         }

         return false;
    }




    /**
     * Determine if the give path match route path
     *
     * @param string $requestPath
     *
     * @return bool
    */
    public function matchRequestPath(string $requestPath): bool
    {
         $pattern = $this->getPattern();
         $path    = $this->resolveRequestPath($requestPath);

         if (preg_match($pattern, $path, $matches)) {

              $this->params = $this->filterMatches($matches);

              $this->options(compact('requestPath'));

              return true;
         }

         return false;
    }





    /**
     * @param string $path
     *
     * @return string
    */
    private function resolveRequestPath(string $path): string
    {
         return (string) parse_url($path ?: '/', PHP_URL_PATH);
    }






    /**
     * @param string $name
     *
     * @param string $regex
     *
     * @return string
    */
    private function resolvePattern(string $name, string $regex): string
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
    private function filterMatches(array $matches): array
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
    private function call(callable $callable): mixed
    {
        return call_user_func_array($callable, array_values($this->params));
    }

}