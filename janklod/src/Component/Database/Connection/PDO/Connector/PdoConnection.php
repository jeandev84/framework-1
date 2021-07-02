<?php
namespace Jan\Component\Database\Connection\PDO\Connector;


use Jan\Component\Database\Builder\Contract\SQLQueryBuilder;
use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Exception\DriverException;
use PDO;


/**
 * Class Connector
 * @package Jan\Component\Database\Connection\PDO\Connector
*/
class PdoConnection extends Connection
{


    protected $options = [
        PDO::ATTR_PERSISTENT => true, // permit to insert/ persist data in to database
        PDO::ATTR_EMULATE_PREPARES => 0,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];



    /**
     * @param array $config
     * @return PdoConnection
     * @throws DriverException
    */
    public function connect(array $config): PdoConnection
    {
        try {

            $this->config->parse($config);

            $driver = $this->config['driver'];

            if (! $this->availableDriver($driver)) {
                throw new DriverException(sprintf('enabled driver (%s) for connection to database.', $driver));
            }

            if ($driver == 'sqlite') {
                $this->config['username'] = null;
                $this->config['password'] = null;
            }

            if (! $this->connected()) {

                $pdo = $this->make(
                    $this->makeDsn(),
                    $this->config['username'],
                    $this->config['password'],
                    $this->getOptions()
                );

                $this->setConnection($pdo);
                $this->setQuery(new Query($pdo));
            }

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
      * Disconnection to the databse
    */
    public function disconnect()
    {
          $this->connection = null;
    }


    /**
     * @param string $sql
    */
    public function exec(string $sql)
    {
        $this->connection->exec($sql);
    }




    /**
     * @param $driver
     * @return bool
    */
    protected function availableDriver($driver): bool
    {
        return \in_array($driver, \PDO::getAvailableDrivers());
    }





    /**
     * @return string
    */
    protected function makeDsn(): string
    {
        return sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s;',
            $this->config['driver'],
            $this->config['host'],
            $this->config['port'],
            $this->config['database'],
            $this->config['charset']
        );
    }



    /**
     * @param string $sql
     * @param array $params
     * @return Query
    */
    public function query(string $sql, array $params = []): Query
    {
         $this->query->setSQL($sql);
         $this->query->setParams($params);

         return $this->query;
    }


    /**
     * Get options
     *
     * @return array
    */
    protected function getOptions(): array
    {
        return array_merge($this->options, $this->config['options']);
    }

    public function makeQueryBuilder(): SQLQueryBuilder
    {
        // TODO: Implement makeQueryBuilder() method.
    }
}