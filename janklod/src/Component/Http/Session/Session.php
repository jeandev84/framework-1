<?php
namespace Jan\Component\Http\Session;


/**
 * Class Session
 * @package Jan\Component\Http\Session
*/
class Session implements SessionInterface
{


    /**
     * Session constructor.
    */
    public function __construct()
    {
         if (! session_status() != PHP_SESSION_NONE) {
              session_start();
         }
    }




    /**
     * @param string $key
     * @param $value
     * @return Session
     */
    public function set(string $key, $value): Session
    {
        $_SESSION[$key] = $value;

        return $this;
    }




    /**
     * @param string $key
     * @return bool
    */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }




    /**
     * @param string $key
     * @param null $default
     * @return mixed
    */
    public function get(string $key, $default = null)
    {
         return $_SESSION[$key] ?? $default;
    }




    /**
     * @param string $key
     * @return void
    */
    public function remove(string $key)
    {
         // remove session
         unset($_SESSION[$key]);
    }



    /**
     * @return array
    */
    public function all(): array
    {
        return $_SESSION;
    }


    /**
     * @return mixed|void
    */
    public function clear()
    {
        foreach (array_keys($_SESSION) as $key) {
             $this->remove($key);
        }

        /* session_destroy(); */
    }



    /**
     * Remove all session params
    */
    public function forget()
    {
        session_destroy();
    }
}