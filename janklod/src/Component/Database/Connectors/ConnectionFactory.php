<?php
namespace Jan\Component\Database\Connectors;


/**
 * Class ConnectionFactory
 * @package Jan\Component\Database\Connectors
*/
class ConnectionFactory
{

    /**
     * @param $name
     *
     * @return mixed
    */
    public function make(array $config, $name)
    {

    }


    /**
     * @param array $config
     * @return MysqlConnector|PostgresConnector|SqliteConnector
    */
    public function createConnector(array $config)
    {
        if (! isset($config['driver'])) {
            throw new \InvalidArgumentException('A driver must be specified.');
        }

        switch ($config['driver']) {
            case 'mysql':
                return new MysqlConnector();
                break;

            case 'sqlite':
                return new SqliteConnector();
                break;

            case 'postgres':
                return new PostgresConnector();
                break;
        }

        throw new \InvalidArgumentException("Unsupported driver [{$config['driver']}].");
    }
}