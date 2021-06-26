<?php
namespace Jan\Component\Database;


use InvalidArgumentException;
use Jan\Component\Database\Connection\ConnectionFactory;
use Jan\Component\Database\Connection\ConnectionInterface;
use Jan\Component\Database\Connection\ConnectionStack;
use Jan\Component\Database\Connection\Contract\ConnectionFactoryInterface;
use Jan\Component\Database\Connection\PDO\MysqlConnection;
use Jan\Component\Database\Connection\PDO\OracleConnection;
use Jan\Component\Database\Connection\PDO\PostgresConnection;
use Jan\Component\Database\Connection\PDO\SqliteConnection;
use Jan\Component\Database\Schema\Schema;


/**
 * Class DatabaseManager
 * @package Jan\Component\Database
*/
class DatabaseManager implements ManagerInterface
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
     * @param string $name (specific : mysql, sqlite, postgres, oracle by default)
     * @param array $config
    */
    public function connect(string $name, array $config)
    {
        if (! $this->connection) {
            $defaultConnections = $this->getDefaultConnections();
            $this->factory->add($defaultConnections);
            $this->setConfigurations($config);
            $this->setDefaultConnection($name);
        }
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

         if (isset($this->connections[$name])) {
             return $this->connections[$name];
         }

         return $this->factory->make($name, $this->configuration($name));
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


    /**
     * @return array
    */
    public function getDefaultConnections(): array
    {
        return ConnectionStack::getPdoConnections();
    }



    /**
     * @return array
    */
    public function supportedDrivers(): array
    {
        return ['mysql', 'pgsql', 'sqlite', 'oci'];
    }
}
