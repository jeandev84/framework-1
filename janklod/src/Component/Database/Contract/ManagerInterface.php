<?php
namespace Jan\Component\Database\Contract;



/**
 * Interface ManagerInterface
 * @package Jan\Component\Database\Contract
*/
interface ManagerInterface extends ManagerConnectionInterface
{

    /**
     * @param array $configParams
     * @return mixed
    */
    public function open(array $configParams);



    /**
     * Close connection
     *
     * @return mixed
    */
    public function close();
}