<?php
namespace Jan\Component\Database;


use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Connection\MySQLi\MySQLiConnection;
use Jan\Component\Database\Connection\PDO\Support\PDOConnection;
use Jan\Component\Database\Contract\ConnectionInterface;


/**
 * Class ConnectionFactory
 * @package Jan\Component\Database
*/
class ConnectionFactory
{

     const TYPE_PDO    = 'pdo';
     const TYPE_MYSQLI = 'mysqli';



     /**
      * @var Configuration
     */
     protected $config;


     /**
      * get connections
      *
      * @var array
     */
     protected $connections = [];



     /**
      * @var mixed
     */
     protected $connection;



      /**
       * ConnectionFactory constructor.
       * @param Configuration $config
     */
     public function __construct(Configuration $config)
     {
          $this->config = $config ;
          $connections  = ConnectionStack::getDefaults($this->config);
          $this->setConnections($connections);
     }



     /**
      * @param array $connections
     */
     public function setConnections(array $connections)
     {
          foreach ($connections as $connection) {
              $this->setConnection($connection);
          }
     }



    /**
     * @param mixed $connection
     * @return $this
    */
    public function setConnection($connection): ConnectionFactory
    {
        if($connection instanceof ConnectionInterface) {
            if($name = $connection->getName()) {
                $this->connections[$name] = $connection;
            }
        } else {
            $this->connection = $connection;
        }

        return $this;
    }



     /**
      * Make connection
      *
      * @param string|null $driver
      * @return mixed
      * @throws \Exception
    */
    public function makeConnection(string $driver)
    {
         if (! \array_key_exists($driver, $this->connections)) {

             if($this->connection) {
                 return $this->connection;
             }

             throw new \Exception(sprintf('Cannot resolve connection for driver (%s)', $driver));
         }

         return $this->connections[$driver];
    }




    /**
     * @return \PDO|null
     * @throws \Exception
    */
    public function getPDO(): ?\PDO
    {
        return $this->checkConnection(self::TYPE_PDO);
    }


    /**
     * @return \mysqli|null
     * @throws \Exception
    */
    public function getMySQLi(): ?\mysqli
    {
        return $this->checkConnection(self::TYPE_MYSQLI);
    }



    /**
     * @return mixed
     * @throws \Exception
    */
    public function getDefaultConnection()
    {
         if(! $driverName =  $this->config->getDriverName()) {
              throw new \Exception('driver name is required. '. __METHOD__);
         }

         return $this->makeConnection($driverName);
    }



    /**
     * @param string $type
     * @return mixed|void|null
     * @throws \Exception
    */
    private function checkConnection(string $type)
    {
        $checkedConnection = null;
        $driverName = $this->config->getDriverName();
        $connection = $this->makeConnection($driverName);

        switch ($type) {
            case self::TYPE_PDO:
                if($connection instanceof PDOConnection) {
                    $checkedConnection = $connection->getConnection();
                }
                break;
            case self::TYPE_MYSQLI:
                if($connection instanceof MySQLiConnection) {
                    $checkedConnection= $connection->getConnection();
                }
                break;
        }

        return $checkedConnection;
    }
}