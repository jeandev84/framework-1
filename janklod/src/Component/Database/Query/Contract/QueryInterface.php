<?php
namespace Jan\Component\Database\Query\Contract;


/**
 * Interface QueryInterface
 * @package Jan\Component\Database\Query\Contract
*/
interface QueryInterface
{


    /**
     * @param string $sql
     * @return mixed
    */
    public function setSQL(string $sql);




    /**
     * @param array $params
     * @return mixed
    */
    public function setParams(array $params);



    /**
     * @return string
    */
    public function getSQL(): string;




    /**
     * @return mixed
    */
    public function getParams();




    /**
     * @return mixed
    */
    public function execute();




    /**
     * Get all result
     *
     * @return mixed
   */
    public function getResult();




    /**
     * Get one or null result
     *
     * @return mixed
    */
    public function getOneOrNullResult();



    /*
    public function getSingleResult();
    public function getArrayResult();
    public function getScalarResult();
    public function getSingleScalarResult();
    public function getNullOrOneResult();
    */


    /**
     * @param string $sql
     * @param array $params
     * @return mixed
    */
    public function log(string $sql, array $params = []);
}