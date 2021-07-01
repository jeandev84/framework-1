<?php
namespace Jan\Component\Routing;


use InvalidArgumentException;

/**
 * Class RouteParser
 * @package Jan\Component\Routing
*/
class RouteParser
{

      const METHOD = 'methods';
      const PATH   = 'path';
      const TARGET = 'target';
      const NAME   = 'name';

      const OPTION_PATH       = 'prefix';
      const OPTION_NAMESPACE  = 'namespace';
      const OPTION_MIDDLEWARE = 'middleware';
      const OPTION_NAME       = 'name';


      /**
        * @var array
      */
      protected $params  = [
          self::METHOD => [],
          self::PATH   => '',
          self::TARGET => '',
          self::NAME   => ''
      ];



     /**
      * @var array
     */
      protected $options = [
        self::OPTION_PATH        => '',
        self::OPTION_NAMESPACE   => '',
        self::OPTION_NAME        => '',
        self::OPTION_MIDDLEWARE  => []
     ];




      /**
       * RouteParser constructor.
       *
       * @param array $params
      */
      public function __construct(array $params = [])
      {
           if ($params) {
               $this->parseParams($params);
           }
      }



      /**
       * RouteConfiguration constructor.
       * @param array $params
       * @return RouteParser
      */
      public function parseParams(array $params): RouteParser
      {
          $this->params = $params;

          return $this;
      }



      /**
       * @param string $key
      */
      public function removeParam(string $key)
      {
          unset($this->params[$key]);
      }



      /**
       * @param array $options
       * @return RouteParser
      */
      public function parseOptions(array $options): RouteParser
      {
           $this->options = array_merge($this->options, $options);

           return $this;
      }


      /**
       * @param $key
       * @param $value
       * @return $this
      */
      public function setOption($key, $value): RouteParser
      {
           $this->options[$key] = $value;

           return $this;
      }



     /**
       * @param $key
     */
     public function removeOption($key)
     {
          unset($this->options[$key]);
     }


     /**
       * Flush all params
     */
     public function flush()
     {
          $this->flushOptions();
     }


     /**
       * Flush only options
     */
     public function flushOptions()
     {
          $this->options = [];
     }



     /**
        * Get methods
        *
        * @return array
     */
     public function getMethods(): array
     {
          $methods = $this->getParam(self::METHOD);

          if(\is_string($methods)) {
              $methods = explode('|', $methods);
          }

          return (array) $methods;
     }



     /**
       * @return mixed|string|null
     */
     public function getPath()
     {
          $path = $this->getParam(self::PATH);

          if($prefix = $this->getOption(static::OPTION_PATH)) {
              $path = rtrim($prefix, '/') . '/'. ltrim($path, '/');
          }

          return $path;
     }


     /**
      * @return mixed|string|null
     */
     public function getTarget()
     {
         $target = $this->getParam(self::TARGET);

         if(\is_string($target) && $namespace = $this->getOption(static::OPTION_NAMESPACE)) {
             $target = rtrim($namespace, '\\') .'\\' . $target;
         }

         return $target;
     }




    /**
     * @return \Exception|mixed|void|null
    */
    public function getMiddlewares()
    {
        return $this->getOption(self::OPTION_MIDDLEWARE, []);
    }


    /**
     * @return \Exception|mixed|null
    */
    public function getNamePrefix()
    {
        return $this->getOption(static::OPTION_NAME, '');
    }



     /**
       * @param string $name
       * @return mixed|null
     */
     public function getParam(string $name)
     {
         if (! \array_key_exists($name, $this->params)) {
             throw new InvalidArgumentException('unavailable route param. ('. $name .')');
         }

         return $this->params[$name];
     }



     /**
      * Get option
      *
      * @param string $name
      * @param null $default
      * @return mixed|null
     */
     public function getOption(string $name, $default = null)
     {
         return $this->options[$name] ?? $default;
     }


     /**
      * @param string $key
      * @return bool
     */
     public function hasOption(string $key): bool
     {
         return \in_array($key, $this->getAvailableOptions());
     }



     /**
      * @return array
     */
     public function getAvailableOptions(): array
     {
         return [
             self::OPTION_PATH,
             self::OPTION_NAMESPACE,
             self::OPTION_MIDDLEWARE,
             self::OPTION_NAME
         ];
     }




    /**
     * @return string[]
    */
    public function getDefaults(): array
    {
        return [
            'path.prefix'      => $this->getOption(self::OPTION_PATH, ''),
            'name.prefix'      => $this->getOption(self::OPTION_NAME, ''),
            'namespace.prefix' => $this->getOption(self::OPTION_NAMESPACE, '')
        ];
    }



    /**
     * @param array $options
     * @return array
    */
    public function configureApiDefaultOptions(array $options): array
    {
        if(! isset($options[self::OPTION_PATH])) {
            $options[self::OPTION_PATH] = 'api';
        }

        return $options;
    }
}
