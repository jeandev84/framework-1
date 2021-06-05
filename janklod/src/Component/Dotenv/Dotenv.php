<?php
namespace Jan\Component\Dotenv;


use Exception;


/**
 * Class Dotenv (load environments data)
 * @package Jan\Component\Dotenv
 */
class Dotenv
{


    /**
     * @var Dotenv
    */
    private static $instance;



    /**
     * @var string
    */
    protected $resource;


    /**
     * Env constructor.
     *
     * @param string $resource
    */
    private function __construct(string $resource)
    {
        $this->resource = rtrim($resource, '\\/');
    }


    /**
     * @param string $resource
     * @return Dotenv
    */
    public static function create(string $resource): Dotenv
    {
         if(! static::$instance) {
             static::$instance = new static($resource);
         }

         return static::$instance;
    }


    /**
     * @param string $filename
     *
     * @return bool
     * @throws Exception
    */
    public function load(string $filename = '.env'): bool
    {
        /** @var Env $env */
        if($environs = $this->loadEnvironments($filename)) {
            foreach ($environs as $env) {
                $env->put();
            }

            return true;
        }

        return false;
    }


    /**
     * @param string $filename
     * @return array
     * @throws Exception
    */
    public function loadEnvironments(string $filename = '.env'): array
    {
        $filename = $this->resource . DIRECTORY_SEPARATOR. $filename;

        if(! file_exists($filename)) {
            return [];
        }

        return $this->filteredEnvirons(file($filename));
    }


    /**
     * @param array $environs
     * @return array
    */
    protected function filteredEnvirons(array $environs): array
    {
        $data = [];

        foreach ($environs as $env) {
            $envObject = new Env($env);
            if($envObject->getParam()) {
                $data[] = new Env($env);
            }
        }

        return $data;
    }



    /**
     * @param string $filename
     * @return array
     * @throws Exception
    */
    public function logicToDiscuss(string $filename = '.env'): array
    {
        $filename = $this->resource . DIRECTORY_SEPARATOR. $filename;

        if(! file_exists($filename) && touch($filename)) {
            $content = file_get_contents(__DIR__.'/stub/env.stub');
            file_put_contents($filename, $content);
        }

        $this->filteredEnvirons(file($filename));
    }
}



/*
try {
    Dotenv::create(__DIR__.'/../')->load();
} catch (Exception $e) {
    die($e->getMessage());
}
echo getenv('APP_NAME');
*/