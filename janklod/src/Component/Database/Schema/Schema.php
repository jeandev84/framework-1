<?php
namespace Jan\Component\Database\Schema;


use Jan\Component\Database\Connection\ConnectionInterface;

/**
 * Class Schema
 * @package Jan\Component\Database\Schema
*/
class Schema
{
      public function __construct(ConnectionInterface $connection)
      {
      }


      /**
       * Create table
       *
       * @param string $table
       * @param \Closure $closure
      */
      public function create(string $table, \Closure $closure)
      {

      }
}