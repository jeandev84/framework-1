<?php
namespace Jan\Component\Database\Connection\PDO;


use Jan\Component\Database\Connection\ConfigurationParser;
use Jan\Component\Database\Connection\ConnectionInterface;
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
     * @throws DriverException
    */
    public function connect(array $config): Connection
    {
        $driver = $config['driver'];

        if (! $this->availableDriver($driver)) {
            throw new DriverException(sprintf('enabled driver (%s) for connection to database.', $driver));
        }

        $dsn = $this->makeDsn($config);

        if ($driver == 'sqlite') {
            $config['username'] = null;
            $config['password'] = null;
        }

        try {
            $this->connection = $this->createPdoConnection($dsn, $config['username'], $config['password'], $config['options']);
        } catch (\PDOException $e) {
            throw $e;
        }

        return $this->parseConfiguration($config);
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
     * @param string $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array $options
     * @return PDO
    */
    public function createPdoConnection(string $dsn, string $username = null, string $password = null, array $options = []): PDO
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
     * @return Connection
    */
    protected function parseConfiguration(array $params): Connection
    {
        $config = new ConfigurationParser();
        $config->parse($params);
        $this->config = $config;

        return $this;
    }




    /**
     * Disconnection
     *
    */
    public function disconnect()
    {
        $this->connection = null;
    }



    /**
     * @param array $config
     * @return string
    */
    protected function makeDsn(array $config): string
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
        return \in_array($driver, \PDO::getAvailableDrivers());
    }

}