<?php
namespace Jan\Component\Database\Connectors\Contract;


/**
 * Interface ConnectorInterface
 * @package Jan\Component\Database\Connectors\Contract
*/
interface ConnectorInterface
{
     public function connect(array $config);
}