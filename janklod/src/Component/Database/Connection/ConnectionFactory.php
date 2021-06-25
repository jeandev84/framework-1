<?php
namespace Jan\Component\Database\Connection;


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
       * @return Connection|null
      */
      public function make(string $name, array $config): ?Connection
      {
          /** @var Connection $connection */
          $connection = $this->createConnection($name);

          if ($connection instanceof Connection) {
              $connection->parseConfiguration($config);
              $connection->connect($config);
          }

          return $connection;
      }



      /**
       * @param string $name
       * @return ConnectionInterface|null
      */
      public function createConnection(string $name): ?ConnectionInterface
      {
          if (! \array_key_exists($name, $this->getDefaultConnections())) {
              return null;
          }

          return $this->getDefaultConnections()[$name];
      }




      /**
       * @return array
      */
      public function supportedDrivers(): array
      {
         return ['mysql', 'pgsql', 'sqlite', 'oci'];
      }




      /**
       * @param array $connections
       * @return array
      */
      public function getDefaultConnections(array $connections = []): array
      {
          return array_merge($this->getPdoStorageConnections(), $connections);
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