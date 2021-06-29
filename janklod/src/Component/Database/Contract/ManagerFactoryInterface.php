<?php
namespace Jan\Component\Database\Contract;


/**
 * Interface ManagerFactoryInterface
 * @package Jan\Component\Database\Contract
*/
interface ManagerFactoryInterface extends ManagerConnectionInterface
{

    /**
     * @param array $config
     * @param string $connection
     * @return mixed
    */
    public function connect(array $config, string $connection);




    /**
     * @param string|null $name
     * @return mixed
    */
    public function connection(string $name = null);



    /**
     * @param string|null $name
     * @return mixed
    */
    public function disconnect(string $name = null);
}