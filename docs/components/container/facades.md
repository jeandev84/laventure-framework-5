### Facades

```php 
require_once __DIR__.'/vendor/autoload.php';


$container = new \Laventure\Component\Container\Container();


class Router
{
     protected $routes = [];

     public function add($path, Closure $handler)
     {
         $this->routes[$path] = $handler;
     }


     public function dispatch(string $requestPath)
     {
          if (! array_key_exists($requestPath, $this->routes)) {
               exit("Route $requestPath not found");
          }

          $this->routes[$requestPath]();
     }
}


$container->singleton(Router::class, Router::class);

class Route extends \Laventure\Component\Container\Facade\Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Router::class;
    }
}


$container->addFacade(new Route);


Route::add('/', function () {
     echo "Hello\n";
});

Route::dispatch('/');
```