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
      * @var
     */
     protected static $instance;



     /**
      * @var DatabaseManager
     */
     protected $database;



     /**
      * @param string $name
      * @param array $configParams
     */
     public function addConnection(string $name, array $configParams)
     {
         $database = new DatabaseManager();
         $database->open($name, $configParams);

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
     public function bootConnection(): Manager
     {
         static::$instance = $this->getDatabase();

         return $this;
     }



     /**
      * @param string|null $name
      * @return mixed
     */
     public function connection(string $name = null)
     {
          return static::$instance->connection($name);
     }




     /**
      * @return mixed
      * @throws \Exception
     */
     public static function instance()
     {
         if (! static::$instance) {
             throw new \Exception('Cannot get globally instance of capsule.');
         }

         return static::$instance;
     }
}