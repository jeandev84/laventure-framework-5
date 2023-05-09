<?php

use App\Controller\GreetingController;

require_once __DIR__ . '/vendor/autoload.php';



$router = new \Laventure\Component\Routing\Router();
$router->domain('http://localhost:8000');


$router->get('/', function () {
    return "Welcome to Laventure framework";
});

$router->get('/greet', function () {
    return "Greeting\n";
});


# add routes
$prefixes = [
    'path' => '/admin',
    'module' => 'Admin',
    'name' => 'admin.',
    'middlewares' => []
];

$router->group($prefixes, function (\Laventure\Component\Routing\Router $router) {

    $router->get('/', [\App\Controller\Admin\PostController::class, 'index']);

    $router->get('/post/{id}', [\App\Controller\Admin\PostController::class, 'index'])
        ->wheres(['id' => '\d+']);

    $router->get('/user/{name?}', [\App\Controller\Admin\PostController::class, 'index'])
        ->where('name',  '\w+');
});



$router->post('/contact', [GreetingController::class, 'contact']);


dump($router->getRoutes());

# /
# /admin
# /contact
# admin/post/1/something?foo=test&bar=nothing


$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestURI    = $_SERVER['REQUEST_URI'];

echo "METHOD: $requestMethod | URI: $requestURI";

echo "<div>". $router->dispatchRoute($requestMethod, $requestURI) ."</div>";

