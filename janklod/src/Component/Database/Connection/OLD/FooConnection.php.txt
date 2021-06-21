<?php
namespace Jan\Component\Database\Connection;


use Closure;
use Jan\Component\Database\Contract\ConnectionInterface;
use Jan\Component\Database\Contract\QueryInterface;
use Jan\Component\Database\Contract\SQLQueryBuilder;

/**
 * Class FooConnection
 * @package Jan\Component\Database\Connection
*/
class FooConnection
{

    private $host;
    private $username;
    private $password;


    /**
     * FooConnection constructor.
     * @param $host
     * @param $username
     * @param $password
    */
    public function __construct($host, $username, $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

}