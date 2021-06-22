<?php
namespace Jan\Component\Database\Connection;


/**
 * Interface ConnectionInterface
 *
 * @package Jan\Component\Database\Connection
*/
interface ConnectionInterface
{
      /**
       * Connect to database
       *
       * @return mixed
      */
      public function connect(array $config);



      /**
       * Determine if has connection
       *
       * @return bool
      */
      public function connected();



      /**
       * Disconnect to database
       *
       * @return mixed
      */
      public function disconnect();
}