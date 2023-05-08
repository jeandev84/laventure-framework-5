### Routes

```php 
require_once __DIR__ . '/vendor/autoload.php';


class GreetingController
{

     public function index($id, $name)
     {
         echo "$id and $name\n";
     }
}



$route1 = new \Laventure\Component\Routing\Route\Route(['GET'], '/');
$route1->domain('http://localhost:8000');
$route1->callback(function () {
    echo "Greeting!\n";
});


if ($route1->match('GET', '/')) {
    $route1->callAnonymous();
    dump($route1);
}


$route2 = new \Laventure\Component\Routing\Route\Route(['GET'], '/post/{id}/{name?}');
$route2->where('id', '\d+');
$route2->where('name', '\w+');
$route2->controller(GreetingController::class, 'index');


if ($route2->match('GET', '/post/2/john')) {
    // echo "Route 2 matches\n";
    // dump($route2);
    $route2->callAction();
} else {
    echo "Not found route\n";
}


if ($route2->match('GET', '/post/1/something?foo=test&bar=nothing')) {
   // echo "Route 2 matches\n";
   // dump($route2);
   $route2->callAction();
} else {
    echo "Not found route\n";
}
```