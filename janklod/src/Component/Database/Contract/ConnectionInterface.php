<?php
namespace Jan\Component\Database\Contract;


use Closure;

/**
 * Interface ConnectionInterface
 * @package Jan\Component\Database\Contract
*/
interface ConnectionInterface
{

    /**
     * Get connection status
     *
     * @return bool
    */
    public function isConnected(): bool;



    /**
     * get name
     *
     * @return string
    */
    public function getName(): string;


    /**
     * get connection
     *
     * @return mixed
    */
    public function getConnection();



    /**
     * @return mixed
    */
    public function beginTransaction();



    /**
     * @return mixed
    */
    public function rollback();



    /**
     * @return mixed
    */
    public function commit();



    /**
     * get last insert id
     * @return int
    */
    public function getLastInsertId(): int;



    /**
     * transaction
     * @param Closure $callback
    */
    public function transaction(Closure $callback);



    /**
     * get connection query
     * @return QueryInterface
    */
    public function getQuery(): QueryInterface;




    /**
     * get query builder
     *
     * @return SQLQueryBuilder
    */
    public function getSQLBuilder(): SQLQueryBuilder;




    /**
     * @param string $sql
     * @return mixed
     */
    public function exec(string $sql);




    /**
     * @param string $sql
     * @param array $params
     * @return QueryInterface
    */
    public function query(string $sql, array $params = []): QueryInterface;




    /**
     * Close connection
     *
     * @return mixed
    */
    public function close();
}