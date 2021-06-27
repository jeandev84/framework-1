<?php
namespace Jan\Component\Database\Connection\PDO;



use Jan\Component\Database\Builder\MysqlQueryBuilder;
use Jan\Component\Database\Connection\PDO\Connector\PdoConnection;


/**
 * Class MysqlConnector
 * @package Jan\Component\Database\Connection\PDO
*/
class MysqlConnector extends PdoConnection
{
     /**
       * @return MysqlQueryBuilder
     */
     public function createQueryBuilder(): MysqlQueryBuilder
     {
          return new MysqlQueryBuilder();
     }
}