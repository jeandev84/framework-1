<?php
namespace Jan\Component\Database\Query;


use Jan\Component\Database\Query\Contract\QueryInterface;


/**
 * Class QueryContract
 *
 * @package Jan\Component\Database\Query
*/
abstract class QueryContract implements QueryInterface
{

    /**
     * @var string
    */
    protected $sql;



    /**
     * @var array
    */
    protected $params;



    /**
     * @param string $sql
     * @return void
    */
    public function setSQL(string $sql)
    {
        $this->sql = $sql;
    }


    /**
     * @return string
    */
    public function getSQL(): string
    {
        return $this->sql;
    }




    /**
     * @param array $params
     * @return void
    */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    

    /**
     * @return array
    */
    public function getParams(): array
    {
        return $this->params;
    }
}