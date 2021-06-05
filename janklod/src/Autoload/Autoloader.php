<?php
namespace Jan\Autoload;


use Jan\Autoload\Exception\AutoloaderException;

/**
 * Class Autoloader
 * @package Jan\Autoload
*/
class Autoloader
{

      /**
       * @var Autoloader
      */
      protected static $instance;


      /**
       * @var string
      */
      protected $root;



      /**
       * @var array
      */
      protected $namespaceMap = [];


      /**
       * Autoloader constructor.
       * @param string $root
       * @throws AutoloaderException
      */
      private function __construct(string $root)
      {
            $this->loadResource($root);
      }


      /**
       * @param string $root
       * @return $this
       * @throws AutoloaderException
      */
      protected function loadResource(string $root): Autoloader
      {
          if (\is_dir($root)) {
               throw new AutoloaderException('root ( '. $root . ' ) is not a directory.');
          }

          $this->root = $root;

          return $this;
      }


      /**
       * @param string $root
       * @return Autoloader|static
       * @throws AutoloaderException
      */
      public static function load(string $root): Autoloader
      {
           if(! self::$instance) {
               self::$instance = new static($root);
           }

           return self::$instance;
      }


      /**
       * Register all classes
      */
      public function register()
      {
          spl_autoload_register([$this, 'autoloadPsr4']);
      }



      /**
       * @param $namespace
       * @param $rootDirectory
       * @return $this
      */
      public function namespaceMap($namespace, $rootDirectory): Autoloader
      {
          $this->namespaceMap[$namespace] = trim($rootDirectory, '\\/');

          return $this;
      }



      /**
        * @param array $namespaces
      */
      public function namespaceMaps(array $namespaces)
      {
           foreach ($namespaces as $namespace => $rootDirectory) {
                $this->namespaceMap($namespace, $rootDirectory);
           }
      }



      /**
       * @return array
      */
      public function getNamespaceMap(): array
      {
           return $this->namespaceMap;
      }



     /**
      * @param $classname
      * @return bool
      * @throws AutoloaderException
      */
      protected function autoloadPsr4($classname): bool
      {
          $classParts = explode('\\', $classname);

          if(\is_array($classParts)) {
             if($filename = $this->processGeneratePathClass($classParts)) {
                 require_once $filename;
                 return true;
             }
          }

          return false;
       }



      /**
       * @param array $classParts
       * @return string
       * @throws AutoloaderException
      */
      protected function processGeneratePathClass(array $classParts): string
      {
          $namespace = array_shift($classParts) .'\\';

          if(! empty($this->namespaceMap[$namespace])) {

             $filename = $this->generatePath($namespace, $classParts);
             $exceptionMessage = sprintf('filename ( %s ) does not exist.', $filename);

             if(! \file_exists($filename)) {
                 throw new AutoloaderException($exceptionMessage);
             }

             return $filename;
          }
       }



      /**
       * @param string $namespace
       * @param array $parts
       * @return string
      */
       protected function generatePath(string $namespace, array $parts): string
       {
          $filename = implode(DIRECTORY_SEPARATOR, [
             $this->root,
             $this->namespaceMap[$namespace],
             implode(DIRECTORY_SEPARATOR, $parts)
          ]);

          return sprintf('%s.php', $filename);
       }
}

/*
$autoloader = Autoloader::load(__DIR__ .'/../../');

$autoloader->namespaceMap('Jan\\', 'src/')
           ->namespaceMap('App\\', 'app/')
;

$autoloader->register();
*/