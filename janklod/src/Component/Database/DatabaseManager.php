<?php
namespace Jan\Component\Database;


use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Connection\Exception\ConnectionException;
use Jan\Component\Database\Connection\MySQLi\MySQLiConnection;
use Jan\Component\Database\Connection\PDO\MySqlConnection;
use Jan\Component\Database\Connection\PDO\OracleConnection;
use Jan\Component\Database\Connection\PDO\PgsqlConnection;
use Jan\Component\Database\Connection\PDO\SQLiteConnection;
use Jan\Component\Database\Connection\PDO\Support\PDOConnection;
use Jan\Component\Database\Contract\ConnectionInterface;
use Jan\Component\Database\Exception\DatabaseException;

/**
 * Class DatabaseManager
 * @package Jan\Component\Database
*/
class DatabaseManager
{


     /**
      * @var Configuration
     */
     protected $config;




     /**
      * @var ConnectionFactory
     */
     protected $factory;



     /**
      * @var mixed
     */
     protected $connection;



     /**
      * Get connection status
      *
      * @var bool
     */
     protected $connected = false;



     /**
      * DatabaseManager constructor.
     */
     public function __construct(array $configParams = [])
     {
          if ($configParams) {
              $this->open($configParams);
          }
     }


     /**
      * @param array $configParams
      * @throws ConnectionException
     */
     public function open(array $configParams)
     {
         if (! $this->connection) {
             $this->config  = new Configuration($configParams);
             $defaultConnections = $this->getDefaultConnections();
             $this->factory = new ConnectionFactory(
                 $this->config->getTypeConnection(),
                 $defaultConnections
             );

             $this->setConnection($this->factory->make());
         }
     }


     /**
      * @param $connection
     */
     public function setConnection($connection)
     {
         if($connection instanceof ConnectionInterface) {
             if ($connection->isConnected()) {
                 $this->setConnectionStatus(true);
             }
         }

         if ($connection instanceof \PDO) {
             $this->setConnectionStatus(true);
         }

         $this->connection = $connection;
     }



    /**
     * @param bool $connected
    */
    public function setConnectionStatus(bool $connected = false)
    {
        $this->connected = $connected;
    }


    /**
      * @return mixed
     */
     public function getConnection()
     {
         return $this->connection;
     }


     public function close()
     {

     }


     /**
      * @return array
      * @throws ConnectionException
     */
     private function getDefaultConnections(): array
     {
         return ConnectionStack::getDefaultConnections($this->config);
     }
}