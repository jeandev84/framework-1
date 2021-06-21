<?php
namespace Jan\Component\Database\Connection\PDO;



use Jan\Component\Database\Connection\PDO\Support\PDOConnection;


/**
 * Class PgsqlConnection
 *
 * @package Jan\Component\Database\Connection\PDO
*/
class PgsqlConnection extends PDOConnection
{
     public function getName(): string
     {
         return 'pgsql';
     }
}