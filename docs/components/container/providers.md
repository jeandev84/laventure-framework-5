### Service Provider

```php
require_once __DIR__.'/vendor/autoload.php';


$container = new \Laventure\Component\Container\Container();


class Filesystem
{
      public function __construct(protected string $root)
      {
      }
}


class FilesystemServiceProvider extends \Laventure\Component\Container\Provider\ServiceProvider
{

    /**
     * @inheritDoc
    */
    public function register(): void
    {
        $this->app->singleton(Filesystem::class, function () {
            return new Filesystem(__DIR__);
        });
    }
}


$container->addProvider(new FilesystemServiceProvider());


dd($container->get(Filesystem::class));
```