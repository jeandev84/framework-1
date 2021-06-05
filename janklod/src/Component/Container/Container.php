<?php
declare(strict_types=1);
namespace Jan\Component\Container;


use Exception;
use Jan\Component\Container\Contract\ContainerContract;
use Jan\Component\Container\Contract\ContainerInterface;
use ReflectionClass;



/**
 * Class Container
 * @package Jan\Component\Container
*/
class Container implements ContainerContract
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
      * @param ContainerInterface|null $instance
     */
     public static function setInstance(ContainerInterface $instance = null): ?ContainerInterface
     {
           return static::$instance = $instance;
     }



     /**
      * Get container instance <Singleton>
      *
      * @return Container|static
     */
     public static function getInstance(): Container
     {
          if(! static::$instance) {
              static::$instance = new static();
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
     public function getConcreteContext(string $abstract)
     {
          if(! $this->hasConcrete($abstract)) {
               return $abstract;
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
      * @param $abstract
      * @return bool
     */
     public function isShared($abstract): bool
     {
         return $this->hasInstance($abstract) || $this->onlyShared($abstract);
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
      * Set instance
      *
      * @param $abstract
      * @param $concrete
      * @return Container
     */
     public function instance($abstract, $concrete): Container
     {
         $this->instances[$abstract] = $concrete;

         return $this;
     }


     /**
      * @param string $abstract
      * @return mixed
      * @throws Exception
     */
     public function factory(string $abstract)
     {
         return $this->make($abstract);
     }


     /**
      * @param string $abstract
      * @param array $parameters
      * @return mixed
      * @throws Exception
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
          return $this->bound($id) || $this->hasInstance($id) || $this->hasAlias($id);
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
      * @throws Exception
     */
     public function get($id)
     {
         try {
             return $this->resolve($id);
         } catch (Exception $e) {
             if($this->has($id)) {
                 throw $e;
             }

             $exceptionMessage = sprintf('Entry %s not found %s', $id, $e->getCode());
             throw new Exception($exceptionMessage, $e);
         }
     }


     /**
      * @param string $abstract
      * @param array $parameters
      * @return mixed
      * @throws Exception
     */
     public function resolve(string $abstract, array $parameters = [])
     {
          // get abstract from alias
          $abstract = $this->getAlias($abstract);

          // get concrete context
          $concrete = $this->getConcreteContext($abstract);

          if($this->canResolve($concrete)) {
              $concrete = $this->resolveConcrete($concrete, $parameters);
              $this->resolved[$abstract] = true;
          }

          if($this->isShared($abstract)) {
               return $this->share($abstract, $concrete);
          }

          return $concrete;
     }



     /**
      * get function dependencies
      *
      * @param array $dependencies
      * @param array $withParams
      * @return array
      * @throws Exception
     */
     public function resolveDependencies(array $dependencies, array $withParams = []): array
     {
         $resolvedDependencies = [];

         foreach ($dependencies as $parameter) {
             $dependency = $parameter->getClass();

             if($parameter->isOptional()) { continue; }
             if($parameter->isArray()) { continue; }

             if(\is_null($dependency)) {
                 if ($parameter->isDefaultValueAvailable()) {
                     $resolvedDependencies[] = $parameter->getDefaultValue();
                 } else {
                     if (array_key_exists($parameter->getName(), $withParams)) {
                         $resolvedDependencies[] = $withParams[$parameter->getName()];
                     } else {
                         $resolvedDependencies = array_merge($resolvedDependencies, $withParams);
                     }
                }
             } else {
                 $resolvedDependencies[] = $this->get($dependency->getName());
             }
         }

         return $resolvedDependencies;
     }



     /**
      * @param $concrete
      * @return bool
     */
     public function canResolve($concrete): bool
     {
          if($concrete instanceof \Closure) {
              return  true;
          }

          if (\is_string($concrete) && \class_exists($concrete)) {
              return true;
          }

          return false;
     }


     /**
      * @param $concrete
      * @param array $withParams
      * @return mixed
      * @throws Exception
     */
     public function resolveConcrete($concrete, array $withParams = [])
     {
         if($concrete instanceof \Closure) {
             return $this->callFunction($concrete, $withParams);
         }

         return $this->makeInstance($concrete, $withParams);
     }



     /**
      * @param string $classMap
      * @param array $withParams
      * @return object
      * @throws \ReflectionException
     */
     public function makeInstance(string $classMap, array $withParams = [])
     {
         try {

             $reflectedClass = new ReflectionClass($classMap);

             if($reflectedClass->isInstantiable()) {

                 $constructor = $reflectedClass->getConstructor();

                 if(\is_null($constructor)) {
                     return $reflectedClass->newInstance();
                 }

                 $dependencies = $this->getMethodDependencies($constructor, $withParams);
                 return $reflectedClass->newInstanceArgs($dependencies);
             }

         } catch (Exception $e) {

              throw $e;
         }
     }


     /**
      * Get method dependencies
      *
      * @param \ReflectionMethod $method
      * @param array $with
      * @return array
      * @throws Exception
     */
     public function getMethodDependencies(\ReflectionMethod $method, array $with = []): array
     {
         return $this->resolveDependencies($method->getParameters(), $with);
     }


     /**
      * @param callable $closure
      * @param array $withParams
      * @return mixed
      * @throws Exception
     */
     public function callFunction(callable $closure, array $withParams = [])
     {
         try {

             $reflectedFunction  = new \ReflectionFunction($closure);
             $functionParameters = $reflectedFunction->getParameters();
             $dependencyParams = $this->resolveDependencies($functionParameters, $withParams);

             return $closure(...$dependencyParams);

         }catch (Exception $e) {
              throw $e;
         }
     }



     /**
      * Flush the container of all bindings and resolved instances.
      *
      * @return void
     */
     public function flush()
     {
         $this->aliases = [];
         $this->resolved = [];
         $this->bindings = [];
         $this->instances = [];
     }




     /**
      * @return array
     */
     public function log(): array
     {
          return [
             'bindings'  => $this->getBindings(),
             'instances' => $this->getInstances(),
             'resolved'  => $this->getResolved(),
             'aliases'   => $this->getAliases()
          ];
     }


     /**
      * @param $abstract
      * @return bool
     */
     protected function onlyShared($abstract): bool
     {
        return isset($this->bindings[$abstract]['shared']) && $this->bindings[$abstract]['shared'] === true;
     }


    /**
     * @param mixed $id
     * @return bool
     */
    public function offsetExists($id): bool
    {
        return $this->has($id);
    }


    /**
     * @param mixed $id
     * @return mixed
     * @throws Exception
     */
    public function offsetGet($id)
    {
        return $this->get($id);
    }


    /**
     * @param mixed $id
     * @param mixed $value
     */
    public function offsetSet($id, $value)
    {
        $this->bind($id, $value);
    }


    /**
     * @param mixed $id
     */
    public function offsetUnset($id)
    {
        unset(
            $this->bindings[$id],
            $this->instances[$id],
            $this->resolved[$id]
        );
    }


    /**
     * @param $name
     * @return array|bool|mixed|object|string|null
     */
    public function __get($name)
    {
        return $this[$name];
    }


    /**
     * @param $name
     * @param $value
    */
    public function __set($name, $value)
    {
        $this[$name] = $value;
    }
}