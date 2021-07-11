<?php
namespace Jan\Express;


/**
 * Class Container
 * @package Jan\Express
*/
class Container
{

     /**
      * @var array
      */
      protected $params = [];




      /**
        * Container constructor.
        * @param array $params
      */
      public function __construct(array $params = [])
      {
            $this->params = $params;
      }




      /**
       * @param string $key
       * @param $value
       * @return $this
      */
      public function bind(string $key, $value): Container
      {
          $this->params[$key] = $value;

          return $this;
      }


      /**
        * @param string $key
        * @return bool
      */
      public function has(string $key): bool
      {
          return isset($this->params[$key]);
      }



      /**
        * @param string $key
        * @return mixed|null
      */
      public function get(string $key)
      {
          if (! $this->has($key)) {
              return null;
          }

          return $this->params[$key];
      }
}