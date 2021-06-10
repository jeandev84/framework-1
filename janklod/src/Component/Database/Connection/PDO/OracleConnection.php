<?php
namespace Jan\Component\Database\Connection\PDO;


use Jan\Component\Database\Connection\PDO\Support\PDOConnection;



/**
 * Class OracleConnection
 *
 * @package Jan\Component\Database\Connection\PDO
*/
class OracleConnection extends PDOConnection
{
    public function getName(): string
    {
        return 'oci';
    }
}