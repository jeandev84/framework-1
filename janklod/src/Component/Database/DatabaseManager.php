<?php
namespace Jan\Component\Database;


use InvalidArgumentException;
use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Connection\ConnectionFactory;
use Jan\Component\Database\Connection\ConnectionStack;
use Jan\Component\Database\Connection\Contract\ConnectionFactoryInterface;
use Jan\Component\Database\Connection\Contract\ConnectionInterface;
use Jan\Component\Database\Contract\ManagerFactoryInterface;
use Jan\Component\Database\Contract\ManagerInterface;



/**
 * Class DatabaseManager
 * @package Jan\Component\Database
*/
class DatabaseManager implements ManagerFactoryInterface
{


     /**
      * @var string
     */
     protected $connection;




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
      * Collect connection status
      *
      * @var array
     */
     protected $status = [];



     /**
      * DatabaseManager constructor.
      * @param array $configParams
     */
     public function __construct(array $configParams = [])
     {
         if ($configParams) {
             $this->setConfigurations($configParams);
         }

         $this->setFactory(new ConnectionFactory());
     }



     /**
      * @param ConnectionFactoryInterface $factory
      * @return DatabaseManager
     */
     public function setFactory(ConnectionFactoryInterface $factory): DatabaseManager
     {
           $this->factory = $factory;

           return $this;
     }



     /**
      * @return ConnectionFactory
     */
     public function getFactory(): ConnectionFactory
     {
          return $this->factory;
     }


    /**
     * Connect to the database
     *
     * (specific : mysql, sqlite, postgres, oracle by default)
     *
     * @param array $config
     * @param string|null $connection
    */
    public function connect(array $config, string $connection)
    {
        if (! $this->connection) {
            $connectors = $this->getConnectors();
            $this->factory->add($connectors);
            $this->configure($connection, $config);
            $this->setDefaultConnection($connection);
            $this->setConnections($connectors); // TODO implements if has registred connection we don't need to make it
        }
    }



    /**
     * @param string $connection
     * @param array $config
    */
    public function configure(string $connection, array $config)
    {
        $data = $config;

        if(! \array_key_exists($connection, $config)) {
            $data = [$connection => $config];
        }

        $this->setConfigurations($data);
    }

    /**
     * @param string $connection
     * @return $this
    */
    public function setDefaultConnection(string $connection): DatabaseManager
    {
        $this->connection = $connection;

        return $this;
    }



    /**
     * @return string
    */
    public function getDefaultConnection(): string
    {
        return $this->connection;
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
      * @param ConnectionInterface $connection
      * @return DatabaseManager
     */
     public function setConnection(string $name, ConnectionInterface $connection): DatabaseManager
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
         foreach ($configParams as $name => $config) {
               if (\is_array($config)) {
                   $this->setConfiguration($name, $config);
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
      * @throws \Exception
     */
     public function connection(string $name = null)
     {
         if (! $name) {
             $name = $this->getDefaultConnection();
         }

         if (! $this->hasConnection($name)) {
             return $this->factory->make($name, $this->configuration($name));
         }

         return $this->connections[$name];
     }
     
     
     
     /**
      * make connection
      *
      * @param string|null $name
      * @return mixed
      * @throws \Exception
     */
     public function makeConnection(string $name, array $config) 
     {
          return $this->factory->make($name, $config);    
     }
     
     
     


     /**
      * @return mixed
      * @throws \Exception
     */
     public function createQueryBuilder(string $alias)
     {
          return $this->connection()->makeQueryBuilder($alias);
     }



     /**
      * @return ConnectionInterface
      * @throws \Exception
     */
     public function getConnection(): ConnectionInterface
     {
         return $this->connection();
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
    public function reconnect(string $name = null)
    {
         // TODO implements
    }



    /**
     * Flush all setting data
     *
    */
    public function flush()
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
        $name = $name ?: $this->getDefaultConnection();
        if (isset($this->connections[$name])) {
            return $this->connections[$name]->disconnect();
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


    /**
     * @return array
    */
    public function getConnectors(): array
    {
        return ConnectionStack::getPdoConnectors();
    }



    /**
     * @return array
    */
    public function supportedDrivers(): array
    {
        return ['mysql', 'pgsql', 'sqlite', 'oci'];
    }
}
