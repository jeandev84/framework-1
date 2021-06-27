<?php
namespace Jan\Component\Database\Connection;


use InvalidArgumentException;
use Jan\Component\Database\Connection\Contract\ConnectionFactoryInterface;
use Jan\Component\Database\Connection\Contract\ConnectionInterface;

/**
 * Class ConnectionFactory
 *
 * @package Jan\Component\Database\Connection
*/
class ConnectionFactory implements ConnectionFactoryInterface
{

      /**
       * @var array
      */
      protected $factories = [];



      /**
        * ConnectionFactory constructor.
      */
      public function __construct(array $factories = [])
      {
           if ($factories) {
               $this->factories = $factories;
           }
      }


      /**
       * @param array $factories
       * @return ConnectionFactory
      */
      public function add(array $factories): ConnectionFactory
      {
           $this->factories = array_merge($this->factories, $factories);

           return $this;
      }



      /**
       * @param string $name
       * @param array $config
       * @return Connection|null
      */
      public function make(string $name, array $config): ?Connection
      {
          if (! \array_key_exists($name, $this->factories)) {
              throw new InvalidArgumentException('Unsupported driver ('. $name .')');
          }

          /** @var Connection $connection */
          $connection = $this->factories[$name];

          if ($connection instanceof Connection) {
              $connection->parseConfiguration($config);
          }

          if ($connection instanceof ConnectionInterface) {
              $connection->connect($config);
              return $connection;
          }

          return null;
      }
}