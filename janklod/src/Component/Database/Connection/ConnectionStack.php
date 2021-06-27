<?php
namespace Jan\Component\Database\Connection;


use Jan\Component\Database\Connection\PDO\MysqlConnector;
use Jan\Component\Database\Connection\PDO\OracleConnector;
use Jan\Component\Database\Connection\PDO\PostgresConnector;
use Jan\Component\Database\Connection\PDO\SqliteConnector;



/**
 * Class ConnectionStack
 * @package Jan\Component\Database\Connection
*/
class ConnectionStack
{

    /**
     * @return array
    */
    public static function getPdoConnectors(): array
    {
        return [
            'mysql'    => new MysqlConnector(),
            'sqlite'   => new SqliteConnector(),
            'postgres' => new PostgresConnector(),
            'oci'      => new OracleConnector(),
        ];
    }
}