<?php
namespace Jan\Component\Database\Connection\PDO;


use Jan\Component\Database\Connection\ConfigurationParser;
use Jan\Component\Database\Connection\PDO\Connector\PdoConnection;


/**
 * Class SqliteConnection
 * @package Jan\Component\Database\Connection\PDO
*/
class SqliteConnection extends PdoConnection
{

     /**
      * @param ConfigurationParser $config
      * @return string
     */
     protected function makeDsn(ConfigurationParser $config): string
     {
         return sprintf('%s:%s', $config['driver'], $config['database']);
     }
}