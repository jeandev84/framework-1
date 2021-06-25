<?php
namespace Jan\Component\Database;


use InvalidArgumentException;
use Jan\Component\Database\Connection\ConnectionFactory;
use Jan\Component\Database\Connection\ConnectionInterface;
use Jan\Component\Database\Schema\Schema;


/**
 * Class DatabaseManager
 * @package Jan\Component\Database
*/
class DatabaseManager implements ManagerInterface
{

     /**
      * @var ConnectionFactory
     */
     protected $factory;



     /**
      * connections storage
      *
      * @var array
     */
     protected $connections = [];



     /**
      * configuration storage
      *
      * @var array
     */
     protected $configurations = [];



     /**
      * @var string
     */
     protected $defaultConnection;



     /**
      * Connection factory (mysql, sqlite, postgres, oracle by default)
      *
      * @param string $connection
      * @param array $config
    */
    public function connect(string $connection, array $config)
    {
        if (! isset($this->connections[$connection])) {
            $this->factory = new ConnectionFactory();
            $connections = $this->factory->getDefaultConnections();
            $this->setDefaultConnection($connection);
            $this->setConfigurations($config);
            $this->setConnections($connections);
        }
    }





    /**
     * @param string $defaultConnection
     * @return $this
     */
    public function setDefaultConnection(string $defaultConnection): DatabaseManager
    {
        $this->defaultConnection = $defaultConnection;

        return $this;
    }



    /**
     * @return string
    */
    public function getDefaultConnection(): string
    {
        return $this->defaultConnection;
    }


    /**
      * @param string $name
      * @return bool
     */
     public function hasConnection(string $name): bool
     {
         return isset($this->connections[$name]);
     }




     /**
      * @param string $name
      * @param $connection
      * @return DatabaseManager
     */
     public function setConnection(string $name, $connection): DatabaseManager
     {
           $this->connections[$name] = $connection;

           return $this;
     }


     /**
      * @param array $connections
      * @return DatabaseManager
     */
     public function setConnections(array $connections): DatabaseManager
     {
         $this->connections = array_merge($this->connections, $connections);

         return $this;
     }


     /**
      * @param array $configParams
      * @return $this
     */
     public function setConfigurations(array $configParams): DatabaseManager
     {
         foreach ($configParams as $connection => $config) {
               if (\is_array($config)) {
                   $this->setConfiguration($connection, $config);
               }
         }

         return $this;
     }


    /**
     * @param string $name
     * @param array $config
     * @return DatabaseManager
     */
    public function setConfiguration(string $name, array $config): DatabaseManager
    {
        $this->configurations[$name] = $config;

        return $this;
    }


    /**
     * get connection configuration params
     *
     * @param string|null $name
     * @return array
    */
    public function configuration(string $name = null): array
    {
        return $this->configurations[$name] ?? [];
    }


     /**
      * get connection
      *
      * @param string|null $name
      * @return mixed
      * @throws Exception\DriverException|\Exception
     */
     public function connection(string $name = null)
     {
         if (! $name) {
             $name = $this->getDefaultConnection();
         }

         $config = $this->configuration($name);

         if (! isset($this->connections[$name])) {
             throw new \Exception('unavailable connection name ('.$name.').');
         }

         $connection = $this->connections[$name];

         if ($connection instanceof ConnectionInterface) {
             $connection = $this->factory->make($name, $config);
         }

         return $connection;
     }




    /**
     * @param string|null $name
    */
    public function purge(string $name = null)
    {
        $this->disconnect($name);

        unset($this->connections[$name]);
    }



    /**
     * Flush all connections
     *
    */
    public function close()
    {
        $this->factory = null;
        $this->configurations = [];
        $this->connections = [];
    }




    /**
     * disconnect connection
     *
     * @param string|null $name
     * @return mixed
    */
    public function disconnect(string $name = null)
    {
        if (isset($this->connections[$name])) {
            $connection = $this->connections[$name];
            if (method_exists($connection, 'disconnect')) {
                return $connection->disconnect();
            }
        }

        throw new InvalidArgumentException('connection ('. $name . ') cannot be disconnected.');
    }




    /**
     * get connections
     *
     * @return array
    */
    public function getConnections(): array
    {
        return $this->connections;
    }



    /**
     * get configurations
     *
     * @return array
    */
    public function getConfigurations(): array
    {
        return $this->configurations;
    }



    public function config(string $key)
    {
        $connection = $this->connection();
    }
}
