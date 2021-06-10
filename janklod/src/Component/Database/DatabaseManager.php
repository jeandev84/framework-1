<?php
namespace Jan\Component\Database;


use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Contract\ConnectionInterface;

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
      * @var ConnectionInterface
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
      * @param array $configParams
      * @throws \Exception
     */
     public function __construct(array $configParams = [])
     {
          if($configParams) {
              $this->open($configParams);
          }
     }



     /**
       * @param array $configParams
       * @throws \Exception
     */
     public function open(array $configParams)
     {
         if(! $this->connection) {

             $config  = new Configuration($configParams);
             $factory = new ConnectionFactory($config);

             $this->setConfiguration($config);
             $this->setFactory($factory);
             $this->setConnection($factory->getDefaultConnection());
         }
     }



     /**
      * @param Configuration $config
      *
      * @return $this
     */
     public function setConfiguration(Configuration $config): DatabaseManager
     {
          $this->config = $config;

          return $this;
     }



     /**
      * @return Configuration
     */
     public function getConfiguration(): Configuration
     {
         return $this->config;
     }




     /**
      * @param ConnectionFactory $factory
      *
      * @return $this
     */
     public function setFactory(ConnectionFactory  $factory): DatabaseManager
     {
          $this->factory = $factory;

          return $this;
     }



     /**
       * Set connection
       *
       * @param $connection
       * @return $this
     */
     public function setConnection($connection): DatabaseManager
     {
          $this->connection = $connection;

          if($this->connection instanceof ConnectionInterface) {
             if ($this->connection->isConnected()) {
                 $this->connected = true;
             }
          }

          if (\is_object($this->connection)) {
                $this->connected = true;
          }

          return $this;
     }



     /**
      * @return mixed
     */
     public function getConnection()
     {
         return $this->connection;
     }



     /**
      * get connection status
      *
      * @return bool
     */
     public function getStatus(): bool
     {
          return $this->connected;
     }




     /**
      * @param string $driver
      * @return mixed
      * @throws \Exception
     */
     public function connection(string $driver)
     {
          $connection = $this->factory->makeConnection($driver);

          $this->setConnection($connection);

          return $connection;
     }




     /**
      * Close
     */
     public function close()
     {
         if($this->connection instanceof Connection) {
             $this->connection->close();
         }

         $this->connection = null;
     }
}