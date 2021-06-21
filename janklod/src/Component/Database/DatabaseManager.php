<?php
namespace Jan\Component\Database;



use Jan\Component\Database\Connectors\Connector;

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
     * @var string
    */
    protected $defaultConnection;



    /**
     * @var array
    */
    protected $connections = [];



    /**
     * @var array
    */
    protected $configurations  = [];




    /**
     * DatabaseManager constructor.
    */
    public function __construct()
    {
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
     * @param string $name
     * @param array $config
     * @return $this
    */
    public function setConfiguration(string $name, array $config): DatabaseManager
    {
         $this->configurations[$name] = $config;

         return $this;
    }


    /**
     * @param string $name
     * @return mixed
    */
    public function configuration(string $name)
    {
         $config = $this->configurations[$name] ?? [];

         return (new ConfigurationParser())
                ->parseConfiguration($config);
    }


    /**
     * @param string $name
     * @param $connection
     * @param array $config
    */
    public function addConnection(string $name, $connection, array $config = [])
    {
         $this->setConnection($name, $connection)
              ->setConfiguration($name, $config);
    }



    /**
     * @param string $name
     * @return array|false|mixed
    */
    public function makeConnection(string $name)
    {
        $configParams = [];
    }


    public function makeConnectionVariant(string $name)
    {
        if (! isset($this->connections[$name])) {
            return $this->getDefaultConnection();
        }

        $connection = $this->connections[$name];

        if (isset($this->configurations[$name])) {
            $configParams = $this->configurations[$name];
            if ($connection instanceof Connector) {
                return $connection->connect($configParams);
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
     * @param $name
    */
    public function setDefaultConnection($name)
    {
        $this->defaultConnection = $name;
    }



    /**
     * @param string $name
    */
    public function connection(string $name = '')
    {

    }


    /**
     * @return array
    */
    public function getConnections(): array
    {
        return $this->connections;
    }



    /**
     * @return array
    */
    public function getConfigurations(): array
    {
        return $this->configurations;
    }


    /**
     * @return array
    */
    public function supportedDrivers(): array
    {
        return ['mysql', 'pgsql', 'sqlite', 'oci'];
    }


    /**
     * @return string[]
    */
    public function availableDrivers(): array
    {
        return array_intersect(
            $this->supportedDrivers(),
            str_replace('', '', \PDO::getAvailableDrivers())
        );
    }



    public function disconnect($name = null)
    {
        if($this->connections[$name]) {
            $this->connections[$name]->disconnect();
        }
    }


    public function purge($name = null)
    {
        $this->disconnect($name);

        unset($this->connections[$name]);
    }
}

