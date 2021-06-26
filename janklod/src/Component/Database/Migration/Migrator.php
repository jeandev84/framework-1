<?php
namespace Jan\Component\Database\Migration;


use Jan\Component\Database\Schema\Schema;

/**
 * Class Migrator
 * @package Jan\Component\Database\Migration
*/
class Migrator
{

      const TBL_MIGRATION = 'migrations';


      /**
       * @var Schema
      */
      protected $schema;



      /**
       * @var string
      */
      protected $migrationTable;



      /**
       * @var array
      */
      protected $migrations;



      /**
        * Migrator constructor.
        * @param Schema $schema
      */
      public function __construct(Schema $schema)
      {
          $this->migrations = [];
          $this->migrationTable = self::TBL_MIGRATION;
          $this->schema = $schema;
      }
}