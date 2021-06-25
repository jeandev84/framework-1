<?php
namespace Jan\Foundation;


use Jan\Component\Container\Container;



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
}