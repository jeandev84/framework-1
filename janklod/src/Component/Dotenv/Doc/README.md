# Dotenv

```
$dotenv = \Jan\Component\Dotenv\Dotenv::create(__DIR__.'/../');
$dotenv->load();

echo getenv('APP_NAME') . "<br>\n";
echo $_ENV['DB_NAME'];

======================================================================

$dotenv = \Jan\Component\Dotenv\Dotenv::create(__DIR__.'/../');
$dotenv->load();

echo getenv('APP_NAME') . "<br>\n";
echo $_ENV['DB_NAME'];

dump($dotenv->loadEnvironments());
```