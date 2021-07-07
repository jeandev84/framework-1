<?php
namespace Jan\Component\Event\Contract;


/**
 * Interface EventInterface
 * @package Jan\Component\Event\Contract
*/
interface EventInterface
{

    /**
     * Get event name
     *
     * @return string
    */
    public function getName(): string;
}