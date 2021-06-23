<?php
namespace Jan\Component\Database\Connection\PDO;


use ArrayAccess;
use Jan\Component\Database\Connection\ConfigurationParser;
use Jan\Component\Database\Connection\ConnectionInterface;
use Jan\Component\Database\Connection\PDO\Statement\Query;
use Jan\Component\Database\Exception\DriverException;
use PDO;

/**
 * Class Connector
 * @package Jan\Component\Database\Connection\PDO
*/
class Connection implements ConnectionInterface
{

    /**
     * @var ConfigurationParser
    */
    protected $config;



    /**
     * @var PDO
    */
    protected $connection;



    /**
     * @param array $config
     * @return Connection
    */
    public function connect(array $config): Connection
    {
        $config = $this->parseConfiguration($config);

        try {
            $driver = $config['driver'];

            if (! $this->availableDriver($driver)) {
                throw new DriverException(sprintf('enabled driver (%s) for connection to database.', $driver));
            }

            if ($driver == 'sqlite') {
                $config['username'] = null;
                $config['password'] = null;
            }

            if (! $this->connected()) {
                $dsn = $this->makeDsn($config);
                $this->connection = $this->make($dsn, $config['username'], $config['password'], $config['options']);
            }

            $this->config = $config;

        } catch (\PDOException $e) {
            throw $e;
        }

        return $this;
    }



    /**
     * @return bool
    */
    public function connected(): bool
    {
        return $this->connection instanceof PDO;
    }



    /**
     * @return PDO
    */
    public function getPdo(): PDO
    {
        return $this->connection;
    }



    /**
     * @return PDO
    */
    public function getConnection(): PDO
    {
        return $this->getPdo();
    }



    /**
     * @return Query
    */
    public function getQuery(): Query
    {
        return new Query($this->getPdo());
    }


    /**
     * @param string $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array|null $options
     * @return PDO
    */
    public function make(string $dsn, string $username = null, string $password = null, array $options = null): PDO
    {
        return new PDO($dsn, $username, $password, $options);
    }



    /**
     * @return ConfigurationParser
    */
    public function getConfiguration(): ConfigurationParser
    {
        return  $this->config;
    }




    /**
     * @param array $params
     * @return ConfigurationParser
    */
    public function parseConfiguration(array $params): ConfigurationParser
    {
        return new ConfigurationParser($params);
    }




    /**
     * Disconnection to the databse
    */
    public function disconnect()
    {
        $this->connection = null;
    }


    /**
     * @param ConfigurationParser $config
     * @return string
    */
    protected function makeDsn(ConfigurationParser $config): string
    {
        return sprintf(
    '%s:host=%s;port=%s;dbname=%s;charset=%s;',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
    }


    /**
     * @param $driver
     * @return bool
   */
    public function availableDriver($driver): bool
    {
        return  \in_array($driver, \PDO::getAvailableDrivers());
    }


}