<?php
namespace Jan\Component\Database\Connection\PDO;


use Jan\Component\Database\Connection\Configuration;
use Jan\Component\Database\Connection\PDO\Connector\PdoConnection;



/**
 * Class SqliteConnection
 * @package Jan\Component\Database\Connection\PDO
*/
class SqliteConnection extends PdoConnection
{

     /**
      * @return string
     */
     protected function makeDsn(): string
     {
         return sprintf('%s:%s', $this->config['driver'], $this->config['database']);
     }
}