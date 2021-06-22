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
      protected $config = [];



      /**
       * @param array $config
       * @return ConfigurationParser
      */
      public function parse(array $config): ConfigurationParser
      {
          $this->config = $config;

          return $this;
      }



     /**
      * @param $name
      * @param null $default
      * @return mixed|null
     */
     public function get($name, $default = null)
     {
         return $this->config[$name] ?? $default;
     }
}