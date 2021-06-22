<?php
namespace Jan\Component\Database\Connection;


use Jan\Component\Database\Connection\PDO\Connection;
use Jan\Component\Database\Connection\PDO\MysqlConnection;
use Jan\Component\Database\Connection\PDO\OracleConnection;
use Jan\Component\Database\Connection\PDO\PostgresConnection;
use Jan\Component\Database\Connection\PDO\SqliteConnection;

/**
 * Class ConnectionFactory
 *
 * @package Jan\Component\Database\Connection
*/
class ConnectionFactory
{
      /**
       * @param string $name
       * @param array $config
       * @return Connection
      */
      public function make(string $name, array $config)
      {
          $connection = $this->createPdoConnection($name);
          $connection->connect($config);
          return $connection;
      }



      /**
       * @param string $driver
       * @return Connection
      */
      public function createPdoConnection(string $driver)
      {
          switch ($driver) {
              case 'mysql':
                  return new MysqlConnection();
                  break;
              case 'sqlite':
                  return new SqliteConnection();
                  break;
              case 'pgsql':
                  return new PostgresConnection();
                  break;
              case 'oci':
                  return new OracleConnection();
                  break;
          }
      }


     /**
       * @return array
     */
     public function supportedDrivers(): array
     {
         return ['mysql', 'pgsql', 'sqlite', 'oci'];
     }

}