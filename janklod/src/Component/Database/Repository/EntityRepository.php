<?php
namespace Jan\Component\Database\Repository;


use Jan\Component\Database\Connection\Contract\ConnectionInterface;
use Jan\Component\Database\Contract\EntityManagerInterface;

/**
 * Class EntityRepository
 * @package Jan\Component\Database\Repository
*/
class EntityRepository
{

      /**
       * @var ConnectionInterface
      */
      protected $em;



      /**
       * @var string
      */
      protected $entityClass;



      /**
        * EntityRepository constructor.
        * @param EntityManagerInterface $em
      */
      public function __construct(EntityManagerInterface $em)
      {
           $this->em = $em;
      }



      /**
       * @param string $alias
      */
      public function createQueryBuilder(string $alias)
      {

      }
}