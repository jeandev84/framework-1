<?php
namespace Jan\Component\Dotenv;


/**
 * Class EnvironFilter
 * @package Jan\Component\Dotenv
*/
class Env
{

     /**
      * @var mixed
     */
     private $parameter;


    /**
     * Env constructor.
     * @param string $env
    */
    public function __construct(string $env)
    {
       if($parameter = $this->filter($env)) {
           $this->parameter = $parameter;
       }
    }


    /**
     * put environ
    */
    public function put()
    {
        if($this->parameter) {
            putenv(sprintf('%s', $this->parameter));
            list($index, $value) = explode("=", $this->parameter);
            $_ENV[$index] = $value;
        }
    }



    /**
     * @param string $env
     * @return false|mixed|string
    */
    public function filter(string $env)
    {
        $env = trim(str_replace("\n", '', $env));

        if(preg_match('#^(.*)=(.*)$#', $env, $matches)) {

            $matchedEnv = str_replace(' ', '', $matches[0]);
            return explode('#', $matchedEnv)[0];
        }

        return false;
    }
}