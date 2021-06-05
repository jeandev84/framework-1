<?php
namespace Jan\Component\FileSystem;


use Jan\Component\FileSystem\Contract\FileLoaderInterface;
use Jan\Component\FileSystem\Exception\FileLoaderException;



/**
 * Class FileLocator
 * @package Jan\Component\FileSystem
 */
class FileLoader extends FileLocator implements FileLoaderInterface
{

    /**
     * @param $maskLink
     * @return array|false
     *
     * $this->resources("/config/*.php")
    */
    public function resources($maskLink)
    {
        return glob($this->localise($maskLink));
    }




    /**
     * @param string $filename
     * @return mixed
    */
    public function load(string $filename)
    {
        if(! $this->exists($filename)) {
            return false;
        }

        return require $this->localise($filename);
    }
}