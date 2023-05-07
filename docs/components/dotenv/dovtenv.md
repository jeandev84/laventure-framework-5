### Dotenv

```php 
require_once __DIR__.'/vendor/autoload.php';


$dotenv = new Laventure\Component\Dotenv\Dotenv(__DIR__);
$dotenv->load();


echo getenv('DB_NAME') . "\n";
echo $_ENV['APP_URL']. "\n";
echo $_SERVER['APP_SECRET']. "\n";

$dotenv->export();
```