<?php
namespace Jan\Foundation;


use Jan\Component\Container\Container;
use Jan\Component\FileSystem\FileSystem;


/**
 * Class Application
 * @package Jan\Foundation
*/
class Application extends Container
{

     const VERSION = '1.0';


     /**
      * Base path of application
      *
      * @var string
     */
     protected $basePath;


     /**
      * Application constructor.
      * @param string|null $basePath
     */
     public function __construct(string $basePath = null)
     {
          if($basePath) {
              $this->setBasePath($basePath);
          }

          $this->registerProcessing();
     }



    /**
     * Register processing
     */
    protected function registerProcessing()
    {
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
        $this->registerCoreContainerAliases();
    }


    /**
     * Register the basic bindings into the container.
     *
     * @return void
    */
    protected function registerBaseBindings()
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance('filesystem', new FileSystem($this->basePath));
    }



    protected function registerBaseServiceProviders()
    {

    }


    protected function registerCoreContainerAliases()
    {

    }


    /**
     * Get the version number of application
     *
     * @return string
    */
    public function version(): string
    {
         return static::VERSION;
    }


   /**
     * Set base path of application
     *
     * @param string $basePath
     * @return $this
   */
   public function setBasePath(string $basePath): Application
   {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPaths();

        return $this;
   }


   protected function bindPaths()
   {

   }


   /**
     * @param string $path
     * @return string
   */
   public function basePath(string $path = ''): string
   {
       return $this->basePath.$this->loadPath($path);
   }



   /**
     * @param string $path
     * @return string|null
   */
   protected function loadPath(string $path = ''): ?string
   {
       return $path ? DIRECTORY_SEPARATOR . $path : $path;
   }
}