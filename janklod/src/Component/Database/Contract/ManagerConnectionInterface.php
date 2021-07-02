<?php
namespace Jan\Component\Database\Contract;


use Jan\Component\Database\Connection\Contract\ConnectionInterface;

/**
 * Interface ManagerConnectionInterface
 * @package Jan\Component\Database\Contract
*/
interface ManagerConnectionInterface
{
     public function getConnection(): ConnectionInterface;
}