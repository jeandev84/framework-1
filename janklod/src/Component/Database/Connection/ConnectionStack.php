<?php
namespace Jan\Component\Database\Connection;


use Jan\Component\Database\Connection\PDO\MysqlConnection;
use Jan\Component\Database\Connection\PDO\OracleConnection;
use Jan\Component\Database\Connection\PDO\PostgresConnection;
use Jan\Component\Database\Connection\PDO\SqliteConnection;



/**
 * Class ConnectionStack
 * @package Jan\Component\Database\Connection
*/
class ConnectionStack
{

    /**
     * @return array
    */
    public static function getPdoConnections(): array
    {
        return [
            'mysql'    => new MysqlConnection(),
            'sqlite'   => new SqliteConnection(),
            'postgres' => new PostgresConnection(),
            'oci'      => new OracleConnection(),
        ];
    }
}