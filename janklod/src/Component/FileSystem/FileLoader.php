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
    */
    public function loadResources($maskLink)
    {
         $files = $this->resources($maskLink);

         foreach ($files as $file) {
             @require_once $file;
         }
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

        return require $this->locate($filename);
    }
}