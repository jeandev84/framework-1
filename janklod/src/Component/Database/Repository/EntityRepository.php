<?php
namespace Jan\Component\Database\Repository;


use Jan\Component\Database\Connection\Contract\ConnectionInterface;

/**
 * Class EntityRepository
 * @package Jan\Component\Database\Repository
*/
class EntityRepository
{
      /**
       * @var string
      */
      protected $entityClass;



      /**
       * @var ConnectionInterface
      */
      protected $connection;


      /**
        * EntityRepository constructor.
        * @param ConnectionInterface $connection
      */
      public function __construct(ConnectionInterface $connection)
      {
           $this->connection = $connection;
      }



      public function createQueryBuilder(string $alias)
      {

      }
}