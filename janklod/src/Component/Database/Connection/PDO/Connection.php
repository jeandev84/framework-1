<?php
namespace Jan\Component\Database\Connection\PDO;


use Jan\Component\Database\Connection\ConfigurationParser;
use Jan\Component\Database\Connection\ConnectionInterface;
use PDO;

/**
 * Class Connector
 * @package Jan\Component\Database\Connection\PDO
*/
class Connection implements ConnectionInterface
{

    /**
     * @var array
    */
    protected $config = [];



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
        $dsn = $this->makeDsn($config);

        try {
            $this->connection = $this->createPdoConnection($dsn, $config['username'], $config['password'], $config['options']);
        } catch (\PDOException $e) {
            throw $e;
        }

        $this->config = $config;

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
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     * @return PDO
    */
    public function createPdoConnection(string $dsn, string $username = '', string $password = '', array $options = []): PDO
    {
        return new PDO($dsn, $username, $password, $options);
    }



    /**
     * @return ConfigurationParser
    */
    public function getConfiguration(): ConfigurationParser
    {
        return (new ConfigurationParser())
               ->parse($this->config);
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
        return sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s;',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
    }
}