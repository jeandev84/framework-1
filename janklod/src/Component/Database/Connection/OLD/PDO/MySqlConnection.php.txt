<?php
namespace Jan\Component\Database\Connection\PDO;


use Closure;
use Jan\Component\Database\Connection\PDO\Support\PDOConnection;
use Jan\Component\Database\Contract\QueryInterface;
use Jan\Component\Database\Contract\SQLQueryBuilder;


/**
 * Class MySqlConnection
 *
 * @package Jan\Component\Database\Connection\PDO
*/
class MySqlConnection extends PDOConnection
{

    public function getName(): string
    {
        return 'mysql';
    }
}