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
          spl_autoload_register([$this, 'autoload']);
      }



      /**
       * @param $namespace
       * @param $rootDirectory
       * @return $this
      */
      public function map($namespace, $rootDirectory): Autoloader
      {
          $this->namespaceMap[$namespace] = trim($rootDirectory, '\\/');

          return $this;
      }


    /**
     * @param $classname
     * @return bool
     * @throws AutoloaderException
    */
    protected function autoload($classname): bool
    {
        $parts = explode('\\', $classname);

        if(is_array($parts)) {

            $namespace = array_shift($parts) .'\\';

            if(! empty($this->namespaceMap[$namespace])) {

                $filename = $this->generateFilename($namespace, $parts);

                if(! \file_exists($filename)) {
                    throw new AutoloaderException('filename ( '. $filename .' ) does not exist.');
                }

                require_once $filename;

                return true;
            }
        }

        return false;
    }


    /**
     * @return array
    */
    public function getMappedNamespaces(): array
    {
        return $this->namespaceMap;
    }


    /**
     * @param $namespace
     * @param $pathParts
     * @return string
     * @throws AutoloaderException
    */
    protected function generateFilename($namespace, $pathParts): string
    {
        $filename = implode(DIRECTORY_SEPARATOR, [
           $this->root,
           $this->namespaceMap[$namespace],
           implode(DIRECTORY_SEPARATOR, $pathParts)
        ]);

        return sprintf('%.php', $filename);
    }
}


$autoloader = Autoloader::load(__DIR__ .'/../../');

$autoloader->map('Jan\\', 'src/')
           ->map('App\\', 'app/')
;

$autoloader->register();