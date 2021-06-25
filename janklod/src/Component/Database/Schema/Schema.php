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
          * @var Connection
         */
         protected $connection;


         /**
          * @var Configuration
         */
         protected $configuration;



         /**
         * Schema constructor.
         * @param Connection $connection
        */
        public function __construct(Connection $connection)
        {
             $this->connection = $connection;
             $this->configuration = $connection->getConfiguration();
        }



      /**
       * Create table
       *
       * @param string $table
       * @param Closure $closure
       */
      public function create(string $table, Closure $closure)
      {
          $table = $this->configuration->prefixTable($table);

          $bluePrint = new BluePrint($table);

          $closure($bluePrint);

          $sql = sprintf("
                  CREATE TABLE IF NOT EXISTS `%s` (%s) 
                  ENGINE=%s DEFAULT CHARSET=%s
                  COMMENT='Table with abuse reports' 
                  AUTO_INCREMENT=1;%s;",
          $table,
                  'dddd',
                  $this->configuration->get('engine'),
                  $this->configuration->get('charset'),
                  'add column'
          );

          dd($sql);
          // $this->connection->exec($sql);
      }


      /**
       * @param string $table
      */
      public function drop(string $table)
      {
          $sql = sprintf("DROP TABLE `%s`;",
              $this->configuration->prefixTable($table)
          );

          $this->connection->exec($sql);
      }



    /**
     * @param $table
     * @return void
     * @throws Exception
    */
    public function dropIfExists($table)
    {
        $sql = sprintf("DROP TABLE IF EXISTS `%s`;",
            $this->configuration->prefixTable($table)
        );

        $this->connection->exec($sql);
    }



    /**
     * @param $table
     * @return void
     * @throws \Exception
    */
    public function truncate($table)
    {
        $sql = sprintf("TRUNCATE TABLE `%s`;",
            $this->configuration->prefixTable($table)
        );

        $this->connection->exec($sql);
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

}