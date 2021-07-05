<?php
namespace Jan\Component\Database\Capsule;


use Exception;
use Jan\Component\Database\DatabaseManager;
use Jan\Component\Database\Exception\DriverException;


/**
 * Class Manager
 * @package Jan\Component\Database\Capsule
*/
class Manager
{

     /**
      * @var DatabaseManager
     */
     protected static $instance;


     /**
      * @var DatabaseManager
     */
     protected $database;



     /**
       * @param array $config
       * @param string $connection
       * @return void
     */
     public function addConnection(array $config, string $connection)
     {
         $database = new DatabaseManager();
         $database->connect($config, $connection);
         $this->database = $database;
     }




     /**
      * @return DatabaseManager
     */
     public function getDatabase(): DatabaseManager
     {
         return $this->database;
     }



     /**
      * boot connection
     */
     public function bootManager(): Manager
     {
         if (! static::$instance) {
             static::$instance = $this->getDatabase();
         }

         return $this;
     }



     /**
       * @param string|null $name
       * @return mixed
       * @throws DriverException|Exception
      */
     public static function connection(string $name = null)
     {
          return static::instance()->connection($name);
     }



     /**
      * @param string|null $id
      * @return void
      * @throws DriverException
     */
     public static function config(string $id)
     {
         $config = static::connection()->getConfiguration();

         return $config->get($id);
     }




     /**
      * @return DatabaseManager
      * @throws Exception
     */
     public static function instance(): DatabaseManager
     {
         if (! static::$instance) {
             throw new Exception('Cannot get instance of manager.');
         }

         return static::$instance;
     }
}

/*
$fs = new \Jan\Component\FileSystem\FileSystem(
    realpath(__DIR__.'/../')
);

$configDb =  $fs->load('config/database.php');

$capsule = new \Jan\Component\Database\Capsule\Manager();
$type = $configDb['connection'];

$capsule->addConnection($type, $configDb);
$capsule->bootAsGlobal();


$connection = \Jan\Component\Database\Capsule\Manager::connection();
$database = \Jan\Component\Database\Capsule\Manager::instance();

dump($database->connection('sqlite'));
dump($database->connection());
*/