<?php
namespace Jan\Component\Database\ORM;


use Jan\Component\Database\Builder\Contract\SQLQueryBuilder;
use Jan\Component\Database\Connection\Configuration;
use Jan\Component\Database\Connection\Connection;
use Jan\Component\Database\Contract\EntityManagerInterface;
use Jan\Component\Database\ORM\Repository\EntityRepository;



/**
 * Class EntityManager
 * @package Jan\Component\Database\ORM
*/
class EntityManager implements EntityManagerInterface
{

    /**
     * @var Connection
    */
    protected $connection;



    /**
     * @var EntityWorker
    */
    protected $entityWorker;



    /**
     * @var array
    */
    protected $repositories = [];




    /**
     * EntityManager constructor.
     * @param Connection $connection
    */
    public function __construct(Connection $connection)
    {
         $this->entityWorker = new EntityWorker($connection);
         $this->connection   = $connection;
    }




    /**
     * @return Connection
    */
    public function getConnection(): Connection
    {
        return $this->connection;
    }




    /**
     * @return Configuration
    */
    public function getConfiguration(): Configuration
    {
        return $this->connection->getConfiguration();
    }


    /**
     * @param string $className
     * @param EntityRepository $repository
    */
    public function registryClass(string $className, EntityRepository $repository)
    {
         $this->repositories[$className] = $repository;
    }



    /**
     * @param string $className
     * @return mixed
    */
    public function getRepository(string $className)
    {
         if (! isset($this->repositories[$className])) {
             return null;
         }

         return $this->repositories[$className];
    }




    /**
     * @return SQLQueryBuilder
    */
    public function createQueryBuilder(): SQLQueryBuilder
    {
        return $this->connection->makeQueryBuilder();
    }



    /**
     * @param $object
    */
    public function updates($object)
    {
        $this->entityWorker->addObjectToUpdate($object);
    }



    /**
     * @param $object
     * @return mixed|void
    */
    public function persist($object)
    {
        $this->entityWorker->addObjectToInsert($object);
    }



    /**
     * @param $object
     * @return mixed|void
    */
    public function remove($object)
    {
        $this->entityWorker->addObjectToDelete($object);
    }



    /**
     * @return mixed|void
    */
    public function flush()
    {
        $this->entityWorker->save();
    }
}