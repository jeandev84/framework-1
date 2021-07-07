<?php
namespace Jan\Component\Event;


/**
 * Class EventDispatcher
 * @package Jan\Component\Event
*/
class EventDispatcher
{

     /**
      * @var array
     */
     protected $listeners = [];



     /**
      * @return array
     */
     public function getListeners(): array
     {
         return $this->listeners;
     }



     /**
      * @param $eventName
      * @param EventListener $listener
      * @return $this
     */
     public function addListener($eventName, EventListener $listener): EventDispatcher
     {
         $this->listeners[$eventName][] = $listener;

         return $this;
     }



    /**
     * Get listeners by event name
     *
     * @param $eventName
     * @return array|mixed
    */
    public function getListenersByEvent($eventName)
    {
        if(! $this->hasListeners($eventName))
        {
            return [];
        }

        return $this->listeners[$eventName];
    }


    /**
     * Determine if has name in listeners
     *
     * @param $eventName
     * @return bool
    */
    public function hasListeners($eventName): bool
    {
        return isset($this->listeners[$eventName]);
    }


    /**
     * @param Event|null $event
    */
    public function dispatch(Event $event = null)
    {
        if (! $event) {
            foreach ($this->getListenersByEvent($event->getName()) as $listener) {
                $listener->handle($event);
            }
        } else{
            foreach ($this->listeners as $eventName => $listener) {
                // TODO implements
            }
        }


    }
}