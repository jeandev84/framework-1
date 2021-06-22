<?php
namespace Jan\Component\Database;


use InvalidArgumentException;
use Jan\Component\Database\Connection\ConnectionFactory;
use Jan\Component\Database\Connection\ConnectionInterface;
use Jan\Component\Database\Connection\PDO\Connection;

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
      * @var
     */
     protected $defaultConnection;



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
    public function __construct()
    {
        $this->factory = new ConnectionFactory();
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
      * @param array $config
      * @return DatabaseManager
     */
     public function addConnection(string $name, $connection, array $config = []): DatabaseManager
     {
           $this->connections[$name] = $connection;

           if ($config) {
               $this->configurations[$name] = $config;
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
     */
     public function connection(string $name = null)
     {
         if (! isset($this->connections[$name])) {

             if($config = $this->configuration($name)) {
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
      * @return string
     */
     public function getDefaultConnection(): string
     {
         return $this->defaultConnection;
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
     * disconnect connection
     *
     * @param string|null $name
     * @return mixed
    */
    public function disconnect(string $name = null)
    {
        if (isset($this->connections[$name])) {
            if ($this->canDisconnect($name)) {
                return $this->connections[$name]->disconnect();
            }
        }
    }



    /**
     * @param string $name
     * @return bool
    */
    protected function canDisconnect(string $name): bool
    {
        return method_exists($this->connections[$name], 'disconnect');
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