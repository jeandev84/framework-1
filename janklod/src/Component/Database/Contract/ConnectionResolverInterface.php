<?php
namespace Jan\Component\Database\Contract;


/**
 * Interface ConnectionResolverInterface
 * @package Jan\Component\Database\Contract
*/
interface ConnectionResolverInterface
{
      /**
       *
       * @param string|null $name
       * @return mixed
      */
      public function connection(string $name = null);




      /**
        * Get the default name of connection
        *
        * @return mixed
      */
      public function getDefaultConnection();




      /**
       * Set the default connection name
       *
       * @param string $name
       * @return mixed
      */
      public function setDefaultConnection(string $name);
}