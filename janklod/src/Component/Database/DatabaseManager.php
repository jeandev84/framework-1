<?php
namespace Jan\Component\Database;


use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Contract\ConnectionInterface;

/**
 * Class DatabaseManager
 * @package Jan\Component\Database
*/
class DatabaseManager
{

     /**
      * @var ConnectionFactory
     */
     protected $factory;



     /**
      * @var ConnectionInterface
     */
     protected $connection;



     /**
      * Get connection status
      *
      * @var bool
     */
     protected $connected = false;



     /**
      * DatabaseManager constructor.
      *
      * @param array $configParams
     */
     public function __construct(array $configParams = [])
     {
     }



     /**
      * @param array $configParams
     */
     public function open(array $configParams)
     {
         if(! $this->connection) {

         }
     }


     /**
       * @param $connection
     */
     public function setConnection($connection)
     {

     }


     /**
      * close connection
     */
     public function close()
     {

     }
}