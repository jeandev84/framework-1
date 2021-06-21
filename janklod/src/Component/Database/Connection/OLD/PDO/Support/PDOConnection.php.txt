<?php
namespace Jan\Component\Database\Connection\PDO\Support;


use Closure;
use Jan\Component\Database\Configuration;
use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Connection\Exception\ConnectionException;
use Jan\Component\Database\Contract\QueryInterface;
use Jan\Component\Database\Contract\SQLQueryBuilder;
use PDO;


/**
 * Class PDOConnection
 * @package Jan\Component\Database\Connection\PDO\Support
*/
abstract class PDOConnection extends Connection
{

     const DEFAULT_PDO_OPTIONS = [
        PDO::ATTR_PERSISTENT => true, // permit to insert/ persist data in to database
        PDO::ATTR_EMULATE_PREPARES => 0,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
     ];


     protected $config;



     /**
      * PDOConnection constructor.
      *
      * @param Configuration $config
      * @throws ConnectionException
     */
     public function __construct(Configuration $config)
     {
          $this->config = $config;

          if(! $this->isConnected()) {
              $this->connect();
          }
     }


     /**
      * @return Configuration
     */
     public function getConfiguration(): Configuration
     {
         return $this->config;
     }



     /**
      * Determine if has connection
      *
      * @return bool
     */
     public function isConnected(): bool
     {
        return $this->connection instanceof \PDO;
     }



    /**
      * @throws ConnectionException
    */
    protected function connect()
    {
        $driverName = $this->config->getTypeConnection();

        if (! \in_array($driverName, \PDO::getAvailableDrivers())) {
            throw new ConnectionException($driverName .' is not available!');
        }

        $dsn = $this->makeDSN($this->config);
        $username = $this->config->getUsername();
        $password = $this->config->getPassword();

        $options = array_merge(self::DEFAULT_PDO_OPTIONS, (array) $this->config->getOptions());

        try {

            $pdo = new \PDO($dsn, $username, $password, $options);
            $this->setConnection($pdo);

        } catch (\PDOException $e) {
            throw $e;
        }
    }


    /**
     * @return PDO
    */
    public function getPdo(): PDO
    {
        return $this->connection;
    }



    /**
     * @param Configuration $config
     * @return string
    */
    protected function makeDSN(Configuration $config): string
    {
        return sprintf('%s:host=%s;port=%s;dbname=%s;',
            $config->getTypeConnection(),
            $config->getHost(),
            $config->getPort(),
            $config->getDatabase()
        );
    }


    public function close()
    {
        $this->connection = null;
    }

}