<?php
namespace Jan\Component\Database\Connection;


use Jan\Component\Database\Connection\PDO\Connection;
use Jan\Component\Database\Connection\PDO\MysqlConnection;
use Jan\Component\Database\Connection\PDO\OracleConnection;
use Jan\Component\Database\Connection\PDO\PostgresConnection;
use Jan\Component\Database\Connection\PDO\SqliteConnection;
use Jan\Component\Database\Exception\DriverException;

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
       * @throws DriverException
      */
      public function make(string $name, array $config): Connection
      {
          if($connection = $this->createPdoConnection($name)) {
              $connection->connect($config);
          }

          return $connection;
      }



      /**
       * @param string $driver
       * @return Connection|null
      */
      public function createPdoConnection(string $driver): ?Connection
      {
          if (! \array_key_exists($driver, $this->getPdoStorageConnections())) {
              return null;
          }

          return $this->getPdoStorageConnections()[$driver];
      }




      /**
       * @return array
      */
      public function supportedDrivers(): array
      {
         return ['mysql', 'pgsql', 'sqlite', 'oci'];
      }



      /**
       * @return string[]
      */
      public function availableDrivers(): array
      {
          return array_intersect(
              $this->supportedDrivers(),
              str_replace('', '', \PDO::getAvailableDrivers())
          );
      }


      /**
       * @param $driver
       * @return bool
      */
      public function availableDriver($driver): bool
      {
          return  \array_key_exists($driver, \PDO::getAvailableDrivers());
      }



      /**
       * @param array $connections
       * @return array
      */
      public function getStorageConnections(array $connections = []): array
      {
          return array_merge(
              $this->getPdoStorageConnections(),
              $connections
          );
      }




      /**
       * @return array
      */
      public function getPdoStorageConnections(): array
      {
          return [
             'mysql'    => new MysqlConnection(),
             'sqlite'   => new SqliteConnection(),
             'postgres' => new PostgresConnection(),
             'oci'      => new OracleConnection(),
          ];
      }

}