<?php
namespace Jan\Component\Http;



use Jan\Component\Http\Bag\FileBag;

/**
 * Class Request
 * @package Jan\Component\Http
 */
class Request
{

    public $files;


    public function __construct(array $files)
    {
        $this->files = new FileBag($files);
    }


    public static function createFromFactory()
    {

    }


    /**
     * @return static
    */
    public static function createFromGlobals(): Request
    {
         return new static($_FILES);
    }


    public static function create()
    {

    }
}