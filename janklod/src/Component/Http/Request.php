<?php
namespace Jan\Component\Http;



/**
 * Class Request
 * @package Jan\Component\Http
 */
class Request extends RequestStack
{
    public static function createFromFactory()
    {

    }


    /**
     * @return static
    */
    public static function createFromGlobals(): Request
    {
         return new static();
    }


    public static function create()
    {

    }
}