<?php
namespace Jan\Component\Http\Session;


/**
 * interface SessionInterface
 * @package Jan\Component\Http\Session
*/
interface SessionInterface
{
     /**
      * @param string $key
      * @param $value
      * @return mixed
     */
     public function set(string $key, $value);



     /**
      * @param string $key
      * @return bool
     */
     public function has(string $key): bool;




     /**
      * @param string $key
      * @return mixed
     */
     public function get(string $key);




     /**
      * @param string $key
      * @return mixed
     */
     public function remove(string $key);




     /**
      * @return mixed
     */
     public function clear();
}