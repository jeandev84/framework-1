<?php
namespace Jan\Component\Database\Capsule;


use Jan\Component\Database\DatabaseManager;


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
         $this->database->connectTo($name, $config);

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
         static::$instance = $this->getDatabase();

         return $this;
     }



     /**
      * @param string|null $name
      * @return mixed
     */
     public static function connection(string $name = null)
     {
          return static::$instance->connection($name);
     }




     /**
      * @return DatabaseManager
      * @throws \Exception
     */
     public static function instance(): DatabaseManager
     {
         if (! static::$instance) {
             throw new \Exception('Cannot get globally instance of capsule.');
         }

         return static::$instance;
     }
}