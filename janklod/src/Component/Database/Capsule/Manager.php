<?php
namespace Jan\Component\Database\Capsule;


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
      * Manager constructor.
      * @param DatabaseManager|null $database
     */
     public function __construct(DatabaseManager $database = null)
     {
          if (! $database) {
              $database = new DatabaseManager();
          }

          $this->database = $database;
     }



     /**
       * @param string $name
       * @param array $config
      * @return Manager
     */
     public function addConnection(string $name, array $config): Manager
     {
         $this->database->connect($name, $config);

         return $this;
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
     public function bootAsGlobal(): Manager
     {
         if (! static::$instance) {
             static::$instance = $this->getDatabase();
         }

         return $this;
     }


     /**
       * @param string|null $name
       * @return mixed
       * @throws DriverException
     */
     public static function connection(string $name = null)
     {
          return static::instance()->connection($name);
     }




     /**
      * @return DatabaseManager
      * @throws \Exception
     */
     public static function instance(): DatabaseManager
     {
         if (! static::$instance) {
             throw new \Exception('Cannot get instance of manager.');
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