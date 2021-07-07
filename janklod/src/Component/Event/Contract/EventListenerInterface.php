<?php
namespace Jan\Component\Event\Contract;


/**
 * Interface EventListenerInterface
 *
 * @package Jan\Component\Event\Contract
*/
interface EventListenerInterface
{

    /**
     * @param EventInterface $event
     * @return mixed
    */
    public function handle(EventInterface $event);
}