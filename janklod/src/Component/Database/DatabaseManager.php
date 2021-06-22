<?php
namespace Jan\Component\Database;


use InvalidArgumentException;
use Jan\Component\Database\Connection\ConnectionFactory;
use Jan\Component\Database\Connection\ConnectionInterface;



/**
 * Class DatabaseManager
 * @package Jan\Component\Database
*/
class DatabaseManager
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
      * DatabaseManager constructor.
     */
     public function __construct(ConnectionFactory $factory = null)
     {
         if (! $factory) {
             $factory = new ConnectionFactory();
         }

         $connections = $factory->getPdoStorageConnections();
         $this->addConnections($connections);

         $this->factory = $factory;
     }



     /**
      * @param string $type
      * @param array $configParams
     */
     public function open(string $type, array $configParams)
     {
         $this->setConfiguration($type, $configParams);
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
     public function addConnections(array $connections): DatabaseManager
     {
         $this->connections = array_merge($this->connections, $connections);

         return $this;
     }



     /**
      * @param array $configurations
      * @return $this
     */
     public function addConfigurations(array $configurations): DatabaseManager
     {
         $this->configurations = array_merge($this->configurations, $configurations);

         return $this;
     }



     /**
       * @param string $name
       * @param $connection
       * @param array $config
       * @return $this
     */
     public function addConnection(string $name, $connection, array $config = []): DatabaseManager
     {
         $this->setConnection($name, $connection);

         if ($config) {
             $this->setConfiguration($name, $config);
         }

         return $this;
     }




     /**
      * @param $name
      * @param $configParams
      * @return DatabaseManager
     */
     public function setConfiguration($name, $configParams): DatabaseManager
     {
         if (isset($configParams[$name])) {
             $configParams = $configParams[$name];
         }

         $this->configurations[$name] = $configParams;

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
         return $this->configurations[$name] ?? $default;
     }


     /**
      * get connection
      *
      * @param string|null $name
      * @return mixed
      * @throws Exception\DriverException
     */
     public function connection(string $name = null)
     {
         if (! isset($this->connections[$name])) {
             if($config = $this->configuration($name)) {
                 /*
                 $connection = $this->factory->make($name, $config);
                 $this->connections[$name] = $connection;
                 return $connection;
                 */
                 return $this->factory->make($name, $config);
             }

             return $name;
         }

         $connection = $this->connections[$name];

         if (isset($this->configurations[$name])) {
             $configParams = $this->configurations[$name];
             if ($connection instanceof ConnectionInterface) {
                 $connection->connect($configParams);
             }
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
}