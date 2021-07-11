<?php
namespace Jan\Component\Database\Connection;


/**
 * Class Configuration
 *
 * @package Jan\Component\Database
*/
class Configuration implements \ArrayAccess
{
      const PREFIX    = 'prefix';
      

      /**
       * @var array
      */
      protected $params = [];



      /**
       * Configuration constructor
       *
       * @param array $params
      */
      public function __construct(array $params = [])
      {
           if ($params) {
               $this->parse($params);
           }
      }



      /**
       * @param array $params
       * @return Configuration
      */
      public function parse(array $params): Configuration
      {
          $this->params = $params;

          return $this;
      }




      /**
       * @param $key
       * @param $value
      */
      public function set($key, $value)
      {
           $this->params[$key] = $value;
      }




      /**
       * @param $key
       * @return bool
      */
      public function has($key): bool
      {
          return \array_key_exists($key, $this->params);
      }




     /**
      * @param $name
      * @param null $default
      * @return mixed|null
     */
     public function get($name, $default = null)
     {
         return $this->params[$name] ?? $default;
     }




     /**
      * Get all config params
      *
      * @return array
     */
     public function getParams(): array
     {
         return $this->params;
     }





     /**
      * @param string $name
      * @return string
     */
     public function prefixTable(string $name): string
     {
         return $this->get(self::PREFIX) . $name;
     }

     
     
     /**
      * Remove config param
      *
      * @param string $name
     */
     public function remove(string $name)
     {
         unset($this->params[$name]);
     }


     /**
      * @param mixed $offset
      * @return bool
     */
     public function offsetExists($offset): bool
     {
        return $this->has($offset);
     }


     /**
      * @param mixed $offset
      * @return mixed|null
     */
     public function offsetGet($offset)
     {
         return $this->get($offset);
     }


     /**
      * @param mixed $offset
      * @param mixed $value
     */
     public function offsetSet($offset, $value)
     {
         $this->set($offset, $value);
     }


     /**
      * @param mixed $offset
     */
     public function offsetUnset($offset)
     {
         $this->remove($offset);
     }
}