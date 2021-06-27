<?php
namespace Jan\Component\Database\Connection\Contract;


/**
 * Interface ConnectionFactoryInterface
 * @package Jan\Component\Database\Connection\Contract
*/
interface ConnectionFactoryInterface
{

      /**
        * @param string $name
        * @param array $config
        * @return mixed
      */
      public function make(string $name, array $config);
}