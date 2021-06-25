<?php
namespace Jan\Component\Database\Connection;


/**
 * Class Connection
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
     * @param array $params
     * @return void
    */
    public function parseConfiguration(array $params)
    {
         $this->config = new Configuration($params);
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
     * @return Configuration
    */
    public function getConfiguration(): Configuration
    {
         return $this->config;
    }


    /**
     * @param string $name
     * @return string
    */
    public function prefixTable(string $name): string
    {
        return $this->config->tableName($name);
    }
}