<?php
namespace Jan\Component\Event;


use Jan\Component\Event\Contract\EventInterface;
use ReflectionClass;


/**
 * Class Event
 * @package Jan\Component\Event
*/
abstract class Event implements EventInterface
{

     /**
      * Event name
      *
      * @var string
     */
     protected $name;




     /**
      * @param string $name
     */
     public function setName(string $name)
     {
         $this->name = $name;
     }




     /**
      * @return string
     */
     public function getName(): string
     {
         if ($this->name) {
             return $this->name;
         }

         return (new ReflectionClass($this))->getShortName();
     }
}