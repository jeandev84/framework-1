<?php
namespace Jan\Foundation;


use Jan\Component\Container\Container;
use Jan\Component\Container\Contract\ContainerInterface;
use Jan\Component\Database\Connection\Configuration;
use Jan\Component\Http\Request;
use Jan\Component\Http\Response;


/**
 * Class Application
 * @package Jan\Foundation
*/
class Application extends Container
{

     /**
      * Base path of application
      *
      * @var string
     */
     protected $basePath;


     /**
      * Collect service providers
      *
      * @var array
     */
     protected $serviceProviders = [];



     /**
      * Collect facades
      *
      * @var array
     */
     protected $facades = [];



     /**
      * Application constructor.
      * @param string|null $basePath
     */
     public function __construct(string $basePath = null)
     {
          if($basePath) {
              $this->setBasePath($basePath);
          }

          $this->registerBaseBindings();
     }



     /**
      * Set base path of application
      *
      * @param string $basePath
      * @return $this
     */
     public function setBasePath(string $basePath): Application
     {
          $this->basePath = rtrim($basePath, '\\/');

          return $this;
     }


     /**
      * Register base bindings
     */
     protected function registerBaseBindings()
     {
          self::setInstance($this);
          $this->instance(Container::class, $this);
          $this->instance(ContainerInterface::class, $this);
          $this->instance('app', $this);

          // Load helpers


          // Load environments
     }




     /**
      * Terminate application
      *
      * @param Request $request
      * @param Response $response
     */
     public function terminate(Request $request, Response $response)
     {
         $request->setMethod('PUT');
         dump($request);
         dump($request->url(), $request->baseUrl());

         $config = new Configuration();

         $response->sendBody();
     }



     /**
      * Load helpers
     */
     protected function loadHelpers()
     {
          require realpath(__DIR__.'/helpers.php');
     }
}