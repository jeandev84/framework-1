<?php
namespace Jan\Component\Database\Connection\Example;


use Jan\Component\Database\Connection\Contract\ConnectionInterface;

/**
 * Class FooConnection
 * @package Jan\Component\Database\Connection\Example
*/
class FooConnection implements ConnectionInterface
{

    public function connect(array $config)
    {
        // TODO: Implement connect() method.
    }

    public function connected()
    {
        // TODO: Implement connected() method.
    }

    public function getConnection()
    {
        // TODO: Implement getConnection() method.
    }

    public function getConfiguration()
    {
        // TODO: Implement getConfiguration() method.
    }

    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

    public function exec(string $sql)
    {
        // TODO: Implement exec() method.
    }

    public function query(string $sql, array $params = [])
    {
        // TODO: Implement query() method.
    }
}