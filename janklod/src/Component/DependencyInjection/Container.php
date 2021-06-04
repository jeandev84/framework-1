<?php
declare(strict_types=1);
namespace Jan\Component\DependencyInjection;


use Jan\Component\DependencyInjection\Contract\ContainerInterface;



/**
 * Class Container
 * @package Jan\Component\DependencyInjection
*/
class Container implements ContainerInterface
{

     /**
      * @var Container
     */
     protected static $instance;



      /**
       * storage all bound params
       *
       * @var array
     */
     protected $bindings = [];



     /**
      * storage all instances
      *
      * @var array
     */
     protected $instances = [];


     /**
      * storage all resolved params
      *
      * @var array
     */
     protected $resolved  = [];



     /**
      * storage all aliases
      *
      * @var array
     */
     protected $aliases = [];



     /**
      * Set container instance
      *
      * @param $instance
     */
     public static function setInstance($instance)
     {
           self::$instance = $instance;
     }



     /**
      * Get container instance <Singleton>
      *
      * @return Container|static
     */
     public static function getInstance(): Container
     {
          if(\is_null(self::$instance)) {
              self::$instance = new static();
          }

          return self::$instance;
     }



     /**
      * Get bindings params
      *
      * @return array
     */
     public function getBindings(): array
     {
          return $this->bindings;
     }


     /**
      * Get all instances
      *
      * @return array
     */
     public function getInstances(): array
     {
         return $this->instances;
     }


     /**
      * Get resolved params
      *
      * @return array
     */
     public function getResolved(): array
     {
         return $this->resolved;
     }



     /**
      * @return array
     */
     public function getAliases(): array
     {
         return $this->aliases;
     }


     /**
      * @param string $abstract
      * @return mixed
     */
     public function getConcrete(string $abstract)
     {
          if(! $this->hasConcrete($abstract)) {
               return null;
          }

          return $this->bindings[$abstract]['concrete'];
     }



     /**
      * @param string $abstract
      * @param null $concrete
      * @param bool $shared
      * @return $this
     */
     public function bind(string $abstract, $concrete = null, bool $shared = false): Container
     {
          if(\is_null($concrete)) {
              $concrete = $abstract;
          }

          $this->bindings[$abstract] = compact('concrete', 'shared');

          return $this;
     }



     /**
      * Bind many params in the container
      *
      * @param array $bindings
     */
     public function binds(array $bindings)
     {
          foreach ($bindings as $bind) {
              list($abstract, $concrete, $shared) = $bind;
              $this->bind($abstract, $concrete, $shared);
          }
     }



     /**
      * Determine if the given param is bound
      *
      * @param $abstract
      * @return bool
     */
     public function bound($abstract): bool
     {
         return isset($this->bindings[$abstract]);
     }




     /**
      * @param string $abstract
      * @param $concrete
      * @return $this|Container
     */
     public function singleton(string $abstract, $concrete): Container
     {
          return $this->bind($abstract, $concrete, true);
     }


     /**
      * Share a parameter
      *
      * @param $abstract
      * @param $concrete
      * @return mixed
     */
     public function share($abstract, $concrete)
     {
          if(! $this->hasInstance($abstract)) {
              $this->instances[$abstract] = $concrete;
          }

          return $this->instances[$abstract];
     }

     /**
      * @param $abstract
     */
     public function isShared($abstract)
     {

     }



     /**
      * @param string $abstract
      * @return mixed
     */
     public function factory(string $abstract)
     {
         return $this->make($abstract);
     }



     /**
      * @param string $abstract
      * @param array $parameters
      * @return mixed
     */
     public function make(string $abstract, array $parameters = [])
     {
          return $this->resolve($abstract, $parameters);
     }


     /**
      * @param $abstract
      * @param $alias
      * @return Container
     */
     public function alias($abstract, $alias): Container
     {
          $this->aliases[$abstract] = $alias;

          return $this;
     }


     /**
      * @param $abstract
      * @return mixed
     */
     public function getAlias($abstract)
     {
        if($this->hasAlias($abstract)) {
            return $this->aliases[$abstract];
        }

        return $abstract;
     }



     /**
      * @param $id
      * @return bool
     */
     public function has($id): bool
     {
          return $this->bound($id) || $this->hasInstance($id) || $this->resolved($id);
     }


     /**
      * @param $id
      * @return bool
     */
     public function hasInstance($id): bool
     {
         return isset($this->instances[$id]);
     }


     /**
      * @param $id
      * @return bool
     */
     public function hasAlias($id): bool
     {
         return isset($this->aliases[$id]);
     }



     /**
      * @param $id
      * @return bool
     */
     public function resolved($id): bool
     {
          return isset($this->resolved[$id]);
     }



     /**
      * @param $abstract
      * @return bool
     */
     protected function hasConcrete($abstract): bool
     {
          return isset($this->bindings[$abstract]) && isset($this->bindings[$abstract]['concrete']);
     }



     /**
      * @param $id
      * @return mixed|null
      * @throws \Exception
     */
     public function get($id)
     {
         try {

             /*
             if(! $this->has($id)) {
                 return $id;
             }
             */

             return $this->resolve($id);

         } catch (\Exception $e) {
               throw $e;
         }
     }



     /**
      * @param string $abstract
      * @param array $parameters
      * @return mixed
     */
     public function resolve(string $abstract, array $parameters = [])
     {
          $abstract = $this->getAlias($abstract);
          $concrete = $this->getConcrete($abstract);

          if($this->isSingleton($abstract)) {

          }

          return $concrete;
     }



     public function resolveDependencies(array $dependencies)
     {
          foreach ($dependencies as $dependency) {

          }
     }
}