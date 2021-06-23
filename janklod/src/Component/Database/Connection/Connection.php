<?php
namespace Jan\Component\Database\Connection;


/**
 * Class Connection
 * @package Jan\Component\Database\Connection
*/
abstract class Connection implements ConnectionInterface
{
    /**
     * @param array $params
     * @return ConfigurationParser
    */
    protected function parseConfiguration(array $params): ConfigurationParser
    {
        return new ConfigurationParser($params);
    }
}