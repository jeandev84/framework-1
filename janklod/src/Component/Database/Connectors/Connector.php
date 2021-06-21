<?php
namespace Jan\Component\Database\Connectors;


use Jan\Component\Database\Connectors\Contract\ConnectorInterface;
use PDO;

/**
 * Class Connector
 * @package Jan\Component\Database\Connectors
*/
class Connector implements ConnectorInterface
{

     /**
      * @var string
     */
     protected $prefix;


     /**
      * @param array $config
      * @return array
     */
     public function connect(array $config)
     {
        // TODO: Implement connect() method.
        $dsn = '';

        $pdo = $this->createPdoConnection(
            $dsn,
            $config['username'],
            $config['password'],
            $config['options']
        );

        $this->prefix = $config['prefix'];
     }



     /**
      * @param string $name
      * @return string
     */
     public function tableName(string $name): string
     {
          return $this->prefix . $name;
     }


     /**
      * @param string $dsn
      * @param string|null $username
      * @param string|null $password
      * @param array $options
      * @return PDO
     */
     public function createPdoConnection(string $dsn, string $username = null, string $password = null, array $options = []): PDO
     {
         return new PDO($dsn, $username, $password, $options);
     }



     /**
      * @param string $host
      * @param string $username
      * @param string $password
      * @return \mysqli
     */
     public function createMysqliConnection(string $host, string $username, string $password)
     {
         return new \mysqli($host, $username, $password);
     }
}