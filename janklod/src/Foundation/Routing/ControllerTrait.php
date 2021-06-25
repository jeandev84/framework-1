<?php
namespace Jan\Foundation\Routing;


use Jan\Component\Container\Container;


/**
 * Trait ControllerTrait
 * @package Jan\Foundation\Routing
*/
trait ControllerTrait
{

    /**
     * @var Container
    */
    protected $container;


    /**
     * @param Container $container
    */
    public function setContainer(Container $container)
    {
         $this->container = $container;
    }


    /**
     * @return Container
    */
    public function getContainer(): Container
    {
        return $this->container;
    }


    /**
     * @param string $id
     * @return mixed|object|string|null
     * @throws \Exception
    */
    public function get(string $id)
    {
        return $this->container->get($id);
    }
}