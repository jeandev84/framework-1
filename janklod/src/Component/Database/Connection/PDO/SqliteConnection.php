<?php
namespace Jan\Component\Database\Connection\PDO;


/**
 * Class SqliteConnection
 * @package Jan\Component\Database\Connection\PDO
*/
class SqliteConnection extends Connection
{

     /**
      * @param array $config
      * @return string
     */
     protected function makeDsn(array $config): string
     {
         return sprintf('%s:%s', $config['driver'], $config['database']);
     }
}