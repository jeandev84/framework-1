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


     /**
      * PDOConnection constructor.
      *
      * @param Configuration $config
      * @throws ConnectionException
     */
     public function __construct(Configuration $config)
     {
          if(! $this->isConnected()) {
              $this->connect($config);
          }
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
      * @param Configuration $config
      * @throws ConnectionException
    */
    protected function connect(Configuration $config)
    {
        $driverName = $config->getDriverName();

        if (! \in_array($driverName, \PDO::getAvailableDrivers())) {
            throw new ConnectionException($driverName .' is not available!');
        }

        $dsn = $this->makeDSN($config);
        $username = $config->getUsername();
        $password = $config->getPassword();

        $options = array_merge(self::DEFAULT_PDO_OPTIONS, (array) $config->getOptions());

        try {

            $pdo = new \PDO($dsn, $username, $password, $options);
            $this->setConnection($pdo);

        } catch (\PDOException $e) {
            throw $e;
        }
    }


    /**
     * @param Configuration $config
     * @return string
    */
    protected function makeDSN(Configuration $config): string
    {
        return sprintf('%s:host=%s;port=%s;dbname=%s;',
            $config->getDriverName(),
            $config->getHost(),
            $config->getPort(),
            $config->getDatabase()
        );
    }
}