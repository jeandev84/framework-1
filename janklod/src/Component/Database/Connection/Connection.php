<?php
namespace Jan\Component\Database\Connection;


use Jan\Component\Database\Builder\Contract\QueryBuilderMakerInterface;
use Jan\Component\Database\Builder\Contract\SQLQueryBuilder;
use Jan\Component\Database\Connection\Contract\ConnectionInterface;
use Jan\Component\Database\Connection\PDO\Connector\Query;
use Jan\Component\Database\Query\Contract\QueryInterface;


/**
 * Class Connection
 *
 * @package Jan\Component\Database\Connection
*/
abstract class Connection implements ConnectionInterface, QueryBuilderMakerInterface
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
     * Connection constructor.
    */
    public function __construct()
    {
         $this->config = new Configuration();
    }



    /**
     * @return Configuration
    */
    public function getConfiguration(): Configuration
    {
        return $this->config;
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
     * @param string $sql
     * @param array $params
     * @return mixed
    */
    abstract public function query(string $sql, array $params = []);


    /**
     * @return SQLQueryBuilder
    */
    abstract public function makeQueryBuilder(): SQLQueryBuilder;
}