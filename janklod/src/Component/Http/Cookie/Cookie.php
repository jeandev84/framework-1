<?php
namespace Jan\Component\Http\Cookie;


/**
 * Class Cookie
 * @package Jan\Component\Http\Cookie
*/
class Cookie
{

    /**
     * @var array
    */
    protected $params = [];


    /**
     * Cookie constructor.
     *
     * @param array $params
    */
    public function __construct(array $params = [])
    {
         if (! $params) {
             $params = $_COOKIE;
         }

         $this->params = $params;
    }


    /**
     * @param string $name
     * @param mixed $value
     * @param int $expired
    */
    public function set(string $name, $value, int $expired = 3600)
    {
          setcookie($name, $value, time() + $expired);
    }


    /**
     * @param string $key
     * @return bool
    */
    public function has(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }




    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
    */
    public function get(string $key, $default = null)
    {
         return $_COOKIE[$key] ?? $default;
    }




    /**
     * @return array
    */
    public function all(): array
    {
        return $_COOKIE;
    }
}