<?php
namespace Jan\Component\Database\Connection\PDO;


use Jan\Component\Database\Configuration;
use Jan\Component\Database\Connection\PDO\Support\PDOConnection;


/**
 * Class SQLiteConnection
 *
 * @package Jan\Component\Database\Connection\PDO
*/
class SQLiteConnection extends PDOConnection
{
     public function getName(): string
     {
         return 'sqlite';
     }


     /**
      * @param Configuration $config
      *
      * @return string
     */
     protected function makeDSN(Configuration $config): string
     {
         return sprintf('%s:%s', $config->getTypeConnection(), $config->getDatabase());
     }
}