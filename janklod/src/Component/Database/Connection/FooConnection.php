<?php
namespace Jan\Component\Database\Connection;


use Closure;
use Jan\Component\Database\Contract\ConnectionInterface;
use Jan\Component\Database\Contract\QueryInterface;
use Jan\Component\Database\Contract\SQLQueryBuilder;

/**
 * Class FooConnection
 * @package Jan\Component\Database\Connection
*/
class FooConnection implements ConnectionInterface
{

    public function getName(): string
    {
         return 'foo';
    }

    public function getConnection()
    {
        // TODO: Implement getConnection() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function getLastInsertId(): int
    {
        // TODO: Implement getLastInsertId() method.
    }

    public function transaction(Closure $callback)
    {
        // TODO: Implement transaction() method.
    }

    public function getStatus(): bool
    {
        // TODO: Implement getStatus() method.
    }

    public function getQuery(): QueryInterface
    {
        // TODO: Implement getQuery() method.
    }

    public function getSQLBuilder(): SQLQueryBuilder
    {
        // TODO: Implement getQueryBuilder() method.
    }

    public function exec(string $sql)
    {
        // TODO: Implement exec() method.
    }

    public function query(string $sql, array $params = []): QueryInterface
    {
        // TODO: Implement query() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}