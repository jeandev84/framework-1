<?php
namespace Jan\Component\Database\ORM;


use Jan\Component\Database\Connection\Connection;



/**
 * Class EntityWorker
 * @package Jan\Component\Database\ORM
*/
class EntityWorker
{


    /**
     * @var array
     */
    protected $entityInsertions = [];




    /**
     * @var array
     */
    protected $entityDeletions = [];




    /**
     * @var array
    */
    protected $entityUpdates = [];



    /**
     * EntityWorker constructor.
     * @param Connection $conn
    */
    public function __construct(Connection $conn)
    {
    }


    /**
     * @param $object
    */
    public function addObjectToInsert($object)
    {
        $this->entityInsertions[] = $object;
    }



    /**
     * @param $object
    */
    public function addObjectToDelete($object)
    {
        $this->entityDeletions[] = $object;
    }



    /**
     * @param $object
    */
    public function addObjectToUpdate($object)
    {
        $this->entityUpdates[] = $object;
    }



    public function save()
    {

    }
}