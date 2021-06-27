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
          * @param Connection $connection
         */
         public function __construct(Connection $connection)
         {
              $this->config     = $connection->getConfiguration();
              $this->connection = $connection;
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

            $sql = sprintf("
                  CREATE TABLE IF NOT EXISTS `%s` (%s) 
                  ENGINE=%s DEFAULT CHARSET=%s
                  COMMENT='Table with abuse reports' 
                  AUTO_INCREMENT=1;%s;",
                  $table,
                  $bluePrint->buildDefaultColumnSql(),
                  $this->config->get('engine'),
                  $this->config->get('charset'),
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
}