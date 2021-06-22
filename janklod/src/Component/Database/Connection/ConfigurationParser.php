<?php
namespace Jan\Component\Database\Connection;


/**
 * Class ConfigurationParser
 *
 * @package Jan\Component\Database
*/
class ConfigurationParser
{

      /**
       * @var array
      */
      protected $params = [];



      /**
       * @param array $params
       * @return ConfigurationParser
      */
      public function parse(array $params): ConfigurationParser
      {
          $this->params = $params;

          return $this;
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
     public function all(): array
     {
         return $this->params;
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
}