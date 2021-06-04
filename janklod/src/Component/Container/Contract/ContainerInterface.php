<?php
namespace Jan\Component\Container\Contract;


/**
 * Interface ContainerInterface
 * @package Jan\Component\Container\Contract
*/
interface ContainerInterface
{

      /**
       * Determine if the given id is in container
       *
       * @param $id
       * @return mixed
      */
      public function has($id);


      /**
       * Get the value of given id
       *
       * @param $id
       * @return mixed
      */
      public function get($id);
}