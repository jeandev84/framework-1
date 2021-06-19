<?php
namespace Jan\Component\Database;


use Jan\Component\Database\Connection\Exception\ConnectionException;
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

             $config  = new Configuration($configParams);
             $this->factory = new ConnectionFactory(
                 $type = $config->getTypeConnection(),
                 ConnectionStack::getDefaultConnections($config)
             );

             $this->config = $config;
             $this->connection($type);
         }
     }


    /**
     * @param bool $connected
    */
    public function setConnectionStatus(bool $connected)
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


     /**
      * @return array
     */
     public function getConnections(): array
     {
         return $this->factory->getConnections();
     }




     /**
      * @param string $name
      * @param $connection
     */
     public function setConnection(string $name, $connection)
     {
          $this->factory->add($name, $connection);
     }



     /**
      * @param string $name
      * @return mixed
      * @throws ConnectionException
     */
     public function connection(string $name)
     {
          $connection = $this->factory->make($name);

          if ($connection instanceof ConnectionInterface) {
             if ($connection->isConnected()) {
                 $this->setConnectionStatus(true);
             }
         }

         if (is_object($connection)) {
             $this->setConnectionStatus(true);
         }

         $this->connection = $connection;

         return $connection;
     }



     /**
      * close connection
     */
     public function close()
     {
         if($this->connection instanceof ConnectionInterface) {
             $this->connection->close();
         }

         $this->config = null;
         $this->factory = null;
         $this->connection = null;
         $this->setConnectionStatus(false);
     }
}