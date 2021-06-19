<?php
namespace Jan\Component\Database;



use Jan\Component\Database\Connection\Exception\ConnectionException;
use Jan\Component\Database\Connection\PDO\MySqlConnection;
use Jan\Component\Database\Connection\PDO\OracleConnection;
use Jan\Component\Database\Connection\PDO\PgsqlConnection;
use Jan\Component\Database\Connection\PDO\SQLiteConnection;
use Jan\Component\Database\Connection\PDO\Support\PDOConnection;
use Jan\Component\Database\Contract\ConnectionInterface;

/**
 * Class ConnectionFactory
 * @package Jan\Component\Database
*/
class ConnectionFactory
{

     /**
      * @var string
     */
     protected $type;



     /**
      * @var array
     */
     protected $connections = [];


     /**
      * ConnectionFactory constructor.
     */
     public function __construct(string $type, array $connections = [])
     {
         $this->type = $type;

         if ($connections) {
             $this->setConnections($connections);
         }
     }



     /**
      * @param $name
      * @param $connection
      * @return ConnectionFactory
     */
     public function addConnection($name, $connection): ConnectionFactory
     {
         $this->connections[$name] = $connection;

         return $this;
     }



     /**
      * @param mixed $connection
     */
     public function setConnection(ConnectionInterface $connection)
     {
         $this->connections[$connection->getName()] = $connection;
     }


     /**
      * @param $connections
     */
     public function setConnections($connections)
     {
         foreach ($connections as $connection) {
             $this->setConnection($connection);
         }
     }


     /**
      * @param string|null $name
      * @return mixed
     */
     public function make(string $name = null)
     {
         if ($name) {
             $this->type = $name;
         }

         if (! \array_key_exists($this->type, $this->connections)) {
             throw new ConnectionException('Cannot resolve type of connection ('. $this->type .')');
         }

         return $this->connections[$this->type];
     }


     /**
      * @return array
     */
     public function getConnections(): array
     {
         return $this->connections;
     }
}