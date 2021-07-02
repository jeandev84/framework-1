<?php
namespace Jan\Component\Database\ORM\Repository;


use Jan\Component\Database\Contract\EntityManagerInterface;

/**
 * Class ServiceRepository
 * @package Jan\Component\Database\ORM\Repository
*/
class ServiceRepository extends EntityRepository
{

     /**
       * ServiceRepository constructor.
       * @param EntityManagerInterface $manager
       * @param string $entityClass
     */
     public function __construct(EntityManagerInterface $manager, string $entityClass)
     {
          parent::__construct($manager);
          $this->entityClass = $entityClass;
     }
}