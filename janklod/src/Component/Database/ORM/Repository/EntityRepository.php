<?php
namespace Jan\Component\Database\ORM\Repository;


use Jan\Component\Database\Connection\Contract\ConnectionInterface;
use Jan\Component\Database\Contract\EntityManagerInterface;


/**
 * Class EntityRepository
 * @package Jan\Component\Database\ORM\Repository
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
          $qb = $this->em->getConnection()->makeQueryBuilder($alias);

          // $qb->select()->from('table', 'alias'); return $qb;
      }
}