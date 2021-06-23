<?php
namespace Jan\Component\Database;


use InvalidArgumentException;
use Jan\Component\Database\Connection\ConnectionFactory;
use Jan\Component\Database\Connection\ConnectionInterface;



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
     protected $type;



     /**
       * @param string $type
       * @param array $configParams
       * @return DatabaseManager
     */
     public function connectTo(string $type, array $configParams): DatabaseManager
     {
         if (! isset($this->connections[$type])) {

             $factory = new ConnectionFactory();
             $this->factory = $factory;
             $this->type = $type;

             $this->setConfigurations($configParams);
             $this->setDefaultConnections($factory);
         }

         return $this;
     }



     /**
      * @param string $type
      * @param array $config
      * @return array|mixed
     */
     public function getDefaultConfiguration(string $type, array $config)
     {
           if (\array_key_exists($type, $config)) {
                return $config[$type];
           }

           return $config;
     }

     /**
      * @return array|mixed
     */
     public function getCurrentConfiguration(string $type, array $config)
     {
         if (\array_key_exists($type, $config)) {
             return $config[$type];
         }

         return $config;
     }



     /**
      * @param ConnectionFactory $factory
     */
     public function setDefaultConnections(ConnectionFactory $factory)
     {
         $connections = $factory->getStorageConnections();
         $this->setConnections($connections);
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
      * @param array $config
      * @return $this
     */
     public function setConfigurations(array $config): DatabaseManager
     {
         $this->configurations = array_merge($this->configurations, $config);

         return $this;
     }



     /**
       * @param string $name
       * @param $connection
       * @param array $config
       * @return $this
     */
     public function addConnection($connection, array $config = [], string $name = 'default'): DatabaseManager
     {
         $this->setConnection($name, $connection);

         if ($config) {
             $this->setConfiguration($name, $config);
         }

         return $this;
     }




     /**
      * @param $name
      * @param $config
      * @return DatabaseManager
     */
     public function setConfiguration($name, $config): DatabaseManager
     {
         $this->configurations[$name] = $config;

         return $this;
     }


     /**
      * get connection configuration params
      *
      * @param string $name
      * @param array|null $default
      * @return array|mixed
     */
     public function configuration(string $name, array $default = null)
     {
         $configuration = $this->configurations[$name] ?? $default;
         unset($configuration[$this->type]);
         return $configuration;
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
             $name = $this->type;
         }

         $configParams = $this->configuration($name);

         if (isset($this->connections[$name])) {
             $connection = $this->connections[$name];

             if ($connection instanceof ConnectionInterface) {
                 $connection = $this->factory->make($name, $configParams);
             }

             return $connection;
         }

        throw new \Exception('no resolvable '. $name);

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

}
