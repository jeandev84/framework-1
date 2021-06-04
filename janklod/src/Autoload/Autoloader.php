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
      public function map($namespace, $rootDirectory): Autoloader
      {
          $this->namespaceMap[$namespace] = trim($rootDirectory, '\\/');

          return $this;
      }



      /**
        * @param array $namespaces
      */
      public function maps(array $namespaces)
      {
           foreach ($namespaces as $namespace => $rootDirectory) {
                $this->map($namespace, $rootDirectory);
           }
      }


     /**
      * @param $classname
      * @return bool
      * @throws AutoloaderException
     */
     protected function autoloadPsr4($classname): bool
     {
        $parts = explode('\\', $classname);

        if(\is_array($parts)) {

            $namespace = array_shift($parts) .'\\';

            if(! empty($this->namespaceMap[$namespace])) {

                $filename = $this->generateFilename($namespace, $parts);
                $exceptionMessage = sprintf('filename ( %s ) does not exist.', $filename);

                if(! \file_exists($filename)) {
                    throw new AutoloaderException($exceptionMessage);
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
     * @param string $namespace
     * @param array $parts
     * @return string
    */
    protected function generateFilename(string $namespace, array $parts): string
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

$autoloader->map('Jan\\', 'src/')
           ->map('App\\', 'app/')
;

$autoloader->register();
*/