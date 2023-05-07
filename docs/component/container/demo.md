### Bindings

```php 
require_once __DIR__.'/vendor/autoload.php';

$container = new \Laventure\Component\Container\Container();

class Foo
{
    /**
     * @param Bar $bar
     * @param int $id
    */
    public function __construct(Bar $bar, int $id = 1)
    {
         echo $bar->update($id);
    }


    public function index(Bar $bar)
    {
         return __FUNCTION__ . " ". get_class($bar) . "\n";
    }
}



class Bar
{
     public function update($id)
     {
          return "Bar update record $id successfully.\n";
     }
}

class Demo {}


class Auth
{
    public function login(string $username, string $password)
    {
         echo "$username $password\n";
    }
}

class SomeInstance
{

}

interface SomeInstanceInterface
{

}


$container->bind('name', 'Jean-Claude');
$container->bind('demo', function () {
     return new Demo();
});

$make1 = $container->make(Foo::class);
$make2 = $container->make(Foo::class);
$factory =  $container->factory(Demo::class);
dump($make1, $make2, $factory);


$container->bind(Bar::class);
$container->singleton(Foo::class, Foo::class);
$container->instance(SomeInstanceInterface::class, SomeInstance::class);
$container->instance(SomeInstance::class, function () {
    return "Hello";
});


dump($container->get('name'));
dump($container->get('demo'));
dump($container->get(Foo::class));
dump($container->get(Foo::class));
dump($container->get(Foo::class));


$container->callAnonymous(function (Bar $bar) {
   return $bar->update(3);
});

$container->callAnonymous(function (Bar $bar, $id, $name){
    echo "$id $name\n";
    return $bar->update(3);
}, ['id' => 3, 'name' => 'Jean-Claude']);


$container->call([Auth::class, 'login'], ['username' => 'brown', 'password' => 'secret12345!']);

dump($container->get(SomeInstanceInterface::class));
dump($container->get(SomeInstance::class));
```