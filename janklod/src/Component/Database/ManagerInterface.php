<?php
namespace Jan\Component\Database;



/**
 * Interface ManagerInterface
 * @package Jan\Component\Database
*/
interface ManagerInterface
{

   /**
    * @param string|null $name
    * @return mixed
   */
   public function connection(string $name = null);



   /**
     * @return mixed
   */
   public function getConnection();

}