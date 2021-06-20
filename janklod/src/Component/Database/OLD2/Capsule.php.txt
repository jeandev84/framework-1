<?php
namespace Jan\Component\Database;


use Jan\Component\Database\Exception\DatabaseException;

/**
 * Class Capsule
 * @package Jan\Component\Database
*/
class Capsule
{

    /**
     * @var DatabaseManager
    */
    protected static $instance;


    /**
     * @var array
    */
    protected $connections = [];


    /**
     * add connection params
     *
     * @param array $config
     * @param string|null $name
     * @return Capsule
    */
    public function addConnection(array $config, string $name = null): Capsule
    {

    }



    /**
     * set globally connection to database
    */
    public function setAsGlobal()
    {

    }



    /**
     * @return DatabaseManager
     * @throws DatabaseException
    */
    public static function getInstance(): DatabaseManager
    {

    }

}