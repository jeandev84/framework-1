<?php
namespace Jan\Component\Database\Connection\Contract;


/**
 * Interface ConnectionInterface
 *
 * @package Jan\Component\Database\Connection\Contract
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
       * @return mixed
      */
      public function getConnection();




      /**
       * @return mixed
      */
      public function getConfiguration();



      /**
       * Disconnect to database
       *
       * @return mixed
      */
      public function disconnect();




      /**
       * @param string $sql
       * @return mixed
      */
      public function exec(string $sql);



      /**
       * @param string $sql
       * @param array $params
       * @return mixed
      */
      public function query(string $sql, array $params = []);
}