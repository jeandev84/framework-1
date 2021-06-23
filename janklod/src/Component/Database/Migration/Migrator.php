<?php
namespace Jan\Component\Database\Migration;


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



      public function __construct()
      {
      }
}