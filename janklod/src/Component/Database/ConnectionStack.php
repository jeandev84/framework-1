<?php
namespace Jan\Component\Database;


use Jan\Component\Database\Connection\Exception\ConnectionException;
use Jan\Component\Database\Connection\PDO\MySqlConnection;
use Jan\Component\Database\Connection\PDO\OracleConnection;
use Jan\Component\Database\Connection\PDO\PgsqlConnection;
use Jan\Component\Database\Connection\PDO\SQLiteConnection;
use Jan\Component\Database\Connection\PDO\Support\PDOConnection;

/**
 * Class ConnectionStack
 * @package Jan\Component\Database
*/
class ConnectionStack
{

    /**
     * @param Configuration $config
     * @return array
     * @throws ConnectionException
    */
    public static function getDefaultConnections(Configuration $config): array
    {
        return [
            new MySQLConnection($config),
            new SQLiteConnection($config),
            new PgsqlConnection($config),
            new OracleConnection($config),
        ];
    }
}