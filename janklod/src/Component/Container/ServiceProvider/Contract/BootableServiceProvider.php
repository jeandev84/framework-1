<?php
namespace Jan\Component\Container\ServiceProvider\Contract;


/**
 * Interface BootableServiceProvider
 *
 * @package Jan\Component\Container\ServiceProvider\Contract
*/
interface BootableServiceProvider
{
    /**
     * Boot service provider
     *
     * @return mixed
    */
    public function boot();
}