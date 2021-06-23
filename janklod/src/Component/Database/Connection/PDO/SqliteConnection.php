<?php
namespace Jan\Component\Database\Connection\PDO;


use Jan\Component\Database\Connection\ConfigurationParser;

/**
 * Class SqliteConnection
 * @package Jan\Component\Database\Connection\PDO
*/
class SqliteConnection extends Connection
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