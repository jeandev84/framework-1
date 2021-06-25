<?php
namespace Jan\Component\FileSystem;


use Jan\Component\FileSystem\Contract\FileLocatorInterface;
use Jan\Component\FileSystem\Exception\FileLocatorException;

/**
 * Class FileLocator
 * @package Jan\Component\FileSystem
*/
class FileLocator implements FileLocatorInterface
{

    /**
     * @var string
    */
    protected $resource;



    /**
     * FileLocator constructor.
     * @param string $resource
    */
    public function __construct(string $resource = '')
    {
        if($resource) {
            $this->resource($resource);
        }
    }



    /**
     * @param string $resource
    */
    public function resource(string $resource)
    {
        $this->resource = rtrim($resource, '\\/');
    }



    /**
     * @param $maskLink
     * @return array|false
     *
     * $this->resources("/config/*.php")
    */
    public function resources($maskLink)
    {
        return glob($this->locate($maskLink));
    }



    /**
     * @param $subDirectory
     * @return string
    */
    public function resourceSubDir($subDirectory): string
    {
        $subDirectory = DIRECTORY_SEPARATOR . rtrim($subDirectory, '\\/');

        if($this->resource) {
            $subDirectory = $this->resource . $subDirectory;
        }

        return $subDirectory;
    }


    /**
     * Generate full path of given filename
     *
     * @param string $filename
     * @return string
    */
    public function locate(string $filename): string
    {
         return implode(DIRECTORY_SEPARATOR, [
             $this->resource,
             $this->resolvePath($filename)
         ]);
    }


    /**
     * @param string $filename
     * @return false|string
     * @throws FileLocatorException
    */
    public function realPath(string $filename)
    {
        $exceptionMessage = sprintf('Cannot get real path of file (%s) because does not exist. log method (%s)', $filename, __METHOD__);

        if(! $this->exists($filename)) {
            throw new FileLocatorException($exceptionMessage);
        }

        return realpath($this->locate($filename));
    }


    /**
     * Determine if the given file exist
     *
     * @param string $filename
     * @return bool
    */
    public function exists(string $filename): bool
    {
        return file_exists($this->locate($filename));
    }


    /**
     * Resolve path
     *
     * @param string $filename
     * @return string
     */
    public function resolvePath(string $filename): string
    {
        $filename = $this->removeTrainingSlashes($filename);
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $filename);
    }


    /**
     * @param string $path
     * @return string
    */
    public function removeTrainingSlashes(string $path): string
    {
        return trim($path, '\\/');
    }
}