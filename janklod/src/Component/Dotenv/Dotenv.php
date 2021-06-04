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
        $environs = $this->loadEnvironments($filename);

        if(! $environs) {
            return false;
        }


        /** @var Env $env */
        foreach ($environs as $env) {
            $env->put();
        }

        return true;
    }


    /**
     * @param string $filename
     * @return array
     * @throws Exception
     */
    public function loadEnvironments(string $filename): array
    {
        $data = [];
        $path = $this->resource . DIRECTORY_SEPARATOR. $filename;

        if(! file_exists($path)) {
            return $data;
        }

        $environs =  file($path);

        foreach ($environs as $env) {
             $envObject = new Env($env);
             if($envObject->getParameter()) {
                 $data[] = new Env($env);
             }
        }

        return $data;
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