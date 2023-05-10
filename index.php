<?php

use App\Controller\GreetingController;

require_once __DIR__ . '/vendor/autoload.php';



$router = new \Laventure\Component\Routing\Router();
$router->domain('http://localhost:8000');
$router->namespace("App\\Controller");

$router->get('/', function () {
    return "Welcome to Laventure framework";
})->name('welcome');

$router->get('/greet', function () {
    return "Greeting\n";
})->name('greet');


# add routes
$prefixes = [
    'path' => '/admin',
    'module' => 'Admin',
    'name' => 'admin.',
    'middlewares' => []
];


$router->group($prefixes, function (\Laventure\Component\Routing\Router $router) {

    $router->get('/', [\App\Controller\Admin\PostController::class, 'index'])->name('post.index');

    $router->get('/post/{id}', [\App\Controller\Admin\PostController::class, 'show'])
        ->wheres(['id' => '\d+'])->name('post.show');

    $router->get('/user/{name?}', [\App\Controller\Admin\UserController::class, 'index'])
        ->where('name',  '\w+')->name('user.index');
});



$router->get('/contact', 'GreetingController@edit')->name('edit');
$router->post('/contact', [GreetingController::class, 'contact'])->name('contact');


dump($router->getRoutes());

# /
# /admin
# /contact
# admin/post/1/something?foo=test&bar=nothing


echo $router->dispatchRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

