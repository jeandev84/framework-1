<?php
namespace Jan\Component\Database;



/**
 * Interface ManagerInterface
 * @package Jan\Component\Database
*/
interface ManagerInterface
{


   /**
     * @param array $config
     * @param string|null $connection
     * @return mixed
   */
   public function connect(array $config, string $connection = null);




   /**
     * @return mixed
   */
   public function getConnection();




   /**
     * @param string|null $name
     * @return mixed
   */
   public function disconnect(string $name = null);
}