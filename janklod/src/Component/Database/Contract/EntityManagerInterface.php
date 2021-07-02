<?php
namespace Jan\Component\Database\Contract;


/**
 * Interface EntityManagerInterface
 * @package Jan\Component\Database\Contract
*/
interface EntityManagerInterface
{


     /**
      * @return mixed
     */
     public function getConnection();




     /**
      * @param $object
      * @return mixed
     */
     public function persist($object);




     /**
      * @param $object
      * @return mixed
     */
     public function remove($object);




     /**
      * @return mixed
     */
     public function flush();
}