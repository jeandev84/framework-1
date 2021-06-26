<?php
namespace Jan\Component\Database\Migration;


use Jan\Component\Database\Schema\Schema;

/**
 * Class Migrator
 * @package Jan\Component\Database\Migration
*/
class Migrator
{
      /**
       * @var string
      */
      protected $migrationTable = 'migrations';



      /**
       * @var array
      */
      protected $migrations = [];



      /**
       * @var array
      */
      protected $migrationFiles = [];




     /**
       * @var Schema
     */
     protected $schema;



     /**
      * Migrator constructor.
      * @param Schema $schema
     */
     public function __construct(Schema $schema)
     {
         $this->schema = $schema;
     }
}