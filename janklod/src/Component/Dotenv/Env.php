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
     private $param;


    /**
     * Env constructor.
     * @param string $env
    */
    public function __construct(string $env)
    {
       if($param = $this->filterParsed($env)) {
           $this->param = $param;
       }
    }


    /**
     * @return false|mixed|string
    */
    public function getParam()
    {
        return $this->param;
    }



    /**
     * put environ
    */
    public function put()
    {
        if(! \is_null($this->param)) {
            putenv(sprintf('%s', $this->param));
            list($index, $value) = explode("=", $this->param);
            $_ENV[$index] = $value;
        }
    }



    /**
     * @param string $env
     * @return string|false
     */
    protected function filterParsed(string $env)
    {
        $env = trim(str_replace("\n", '', $env));
        if(preg_match('#^(.*)=(.*)$#', $env, $matches)) {
            return str_replace([' ', '#'], '', $matches[0]);
        }

        return false;
    }
}