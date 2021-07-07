<?php
namespace Jan\Component\Database\Schema;


use Closure;
use Exception;
use Jan\Component\Database\Connection\Configuration;
use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\DatabaseManager as Manager;
use Jan\Component\Database\Exception\DriverException;


/**
 * Class Schema
 * @package Jan\Component\Database\Schema
*/
class Schema
{

         /**
          * @var Configuration
         */
         protected $config;



         /**
          * @var Connection
         */
         protected $connection;




         /**
          * Schema constructor.
          *
          * @param Connection|null $connection
         */
         public function __construct(Connection $connection = null)
         {
              if ($connection) {
                  $this->bootParams($connection);
              }
         }




         /**
           * Set connection
           *
           * @param Connection $connection
         */
         public function setConnection(Connection $connection)
         {
             $this->connection = $connection;
         }





         /**
          * Set configuration
          *
          * @param Configuration $config
         */
         public function setConfiguration(Configuration $config)
         {
              $this->config = $config;
         }




        /**
         * Create table
         *
         * @param string $table
         * @param Closure $closure
         * @return void
        */
        public function create(string $table, Closure $closure)
        {
            $table = $this->table($table);

            $bluePrint = new BluePrint($table);

            $closure($bluePrint);

            // TODO refactoring , we'll don't add altered column sql
            $sql = sprintf("
                  CREATE TABLE IF NOT EXISTS `%s` (%s) 
                  ENGINE=%s DEFAULT CHARSET=%s
                  COMMENT='Table with abuse reports' 
                  AUTO_INCREMENT=1;%s;",
                  $table,
                  $bluePrint->buildDefaultColumnSql(),
                  $this->config['engine'],
                  $this->config['charset'],
                  $bluePrint->buildAlteredColumnSql()
            );

            /* dump($sql); */
            $this->connection->exec($sql);
        }



        /**
         * @param string $table
        */
        public function drop(string $table)
        {
            $sql = sprintf("DROP TABLE `%s`;", $this->table($table));

            $this->connection->exec($sql);
        }



       /**
        * @param $table
        * @return void
        * @throws Exception
       */
       public function dropIfExists($table)
       {
          $sql = sprintf("DROP TABLE IF EXISTS `%s`;", $this->table($table));

          $this->connection->exec($sql);
       }



      /**
       * @param $table
       * @return void
       * @throws
      */
      public function truncate($table)
      {
            $sql = sprintf("TRUNCATE TABLE `%s`;", $this->table($table));

            $this->connection->exec($sql);
      }



     /**
      * @return Connection
     */
     public function getConnection(): Connection
     {
           return $this->connection;
     }



     public function backup()
     {
        // TODO implements
     }



     public function export()
     {
        // TODO implements
     }



    /**
     * Import
    */
    public function import()
    {
        // TODO implements
    }


    /**
     * @param $table
     * @return string
    */
    public function table($table): string
    {
        return $this->config->prefixTable($table);
    }




    /**
     * @param string $sql
     * @param array $params
     * @return mixed
    */
    public function execute(string $sql, array $params = [])
    {
        if ($params) {
            return $this->connection->query($sql, $params);
        }

        return $this->connection->exec($sql);
    }



    /**
     * @param Connection $connection
    */
    public function bootParams(Connection $connection)
    {
         $this->setConnection($connection);
         $this->setConfiguration($connection->getConfiguration());
    }
}