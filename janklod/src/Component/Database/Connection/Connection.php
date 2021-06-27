<?php
namespace Jan\Component\Database\Connection;


use Jan\Component\Database\Connection\Contract\ConnectionInterface;
use Jan\Component\Database\Connection\PDO\Connector\Query;
use Jan\Component\Database\Query\Contract\QueryInterface;


/**
 * Class Connection
 *
 * @package Jan\Component\Database\Connection
*/
abstract class Connection implements ConnectionInterface
{

    /**
     * @var Configuration
    */
    protected $config;



    /**
     * @var mixed
    */
    protected $connection;



    /**
     * @var QueryInterface
    */
    protected $query;




    /**
     * @param Configuration $config
    */
    public function setConfiguration(Configuration $config)
    {
         $this->config = $config;
    }



    /**
     * @param $connection
    */
    public function setConnection($connection)
    {
         $this->connection = $connection;
    }



    /**
     * @return mixed
    */
    public function getConnection()
    {
        return $this->connection;
    }


    /**
     * @param QueryInterface $query
     * @return $this
    */
    public function setQuery(QueryInterface $query): Connection
    {
        $this->query = $query;

        return $this;
    }


    /**
     * @return QueryInterface
    */
    public function getQuery(): QueryInterface
    {
         return $this->query;
    }



    /**
     * @return Configuration
    */
    public function getConfiguration(): Configuration
    {
         return $this->config;
    }


    abstract public function query(string $sql, array $params = []);
}