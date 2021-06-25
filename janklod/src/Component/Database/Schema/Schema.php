<?php
namespace Jan\Component\Database\Schema;


use Jan\Component\Database\DatabaseManager as Manager;
use Jan\Component\Database\Exception\DriverException;


/**
 * Class Schema
 * @package Jan\Component\Database\Schema
*/
class Schema
{


      /**
       * @var Manager
      */
      protected $manager;


      /**
       * Schema constructor.
       * @param Manager $manager
      */
      public function __construct(Manager $manager)
      {
          $this->manager = $manager;
      }


      /**
       * Create table
       *
       * @param string $table
       * @param \Closure $closure
       * @throws DriverException
      */
      public function create(string $table, \Closure $closure)
      {
          $table = $this->getTableName($table);
          $bluePrint = new BluePrint($table);

          $closure($table);

          $sql = sprintf(
              "CREATE TABLE IF NOT EXISTS `%s` (%s) ENGINE=%s DEFAULT CHARSET=%s
           COMMENT='Table with abuse reports' AUTO_INCREMENT=1;%s;",
          $table, '', '');

          $this->manager->connection()->exec($sql);
      }


      /**
       * @param string $table
       * @throws DriverException
      */
      public function drop(string $table)
      {
          $sql = sprintf(
       "DROP TABLE `%s`;",
              $this->getTableName($table)
          );

          $this->manager->connection()->exec($sql);
      }



      /**
       * @param string $name
       * @return mixed
       * @throws DriverException
      */
      public function getTableName(string $name)
      {
         return  $this->manager->connection()
                               ->getConfiguration()
                               ->tableName($name);
      }


      /**
       * @param $table
       * @param $columns
       * @param $engine
       * @param $charset
       * @param $addColumns
       * @return string
      */
      public function buildSqlCreateTable($table, $columns, $engine, $charset, $addColumns): string
      {
          return sprintf(
              "CREATE TABLE IF NOT EXISTS `%s` (%s) 
                     ENGINE=%s DEFAULT CHARSET=%s
                     COMMENT='Table with abuse reports' AUTO_INCREMENT=1;%s;
                     ", $table, $columns, $engine, $charset, $addColumns
          );
      }
}