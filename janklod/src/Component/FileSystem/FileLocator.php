<?php
namespace Jan\Component\FileSystem;


use Jan\Component\FileSystem\Exception\FileLocatorException;

/**
 * Class FileLocator
 * @package Jan\Component\FileSystem
*/
class FileLocator
{

    /**
     * @var string
    */
    protected $resourceDirectory;



    /**
     * FileLocator constructor.
     * @param string $resourceDirectory
     */
    public function __construct(string $resourceDirectory = '')
    {
        if($resourceDirectory) {
            $this->resource($resourceDirectory);
        }
    }


    /**
     * @param string $resourceDirectory
    */
    public function resource(string $resourceDirectory)
    {
        $this->resourceDirectory = rtrim($resourceDirectory, '\\/');
    }


    /**
     * @param $subDirectory
     * @return string
    */
    public function resourceSubDir($subDirectory): string
    {
        $subDirectory = DIRECTORY_SEPARATOR . rtrim($subDirectory, '\\/');

        if($this->resourceDirectory) {
            return $this->resourceDirectory . $subDirectory;
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
             $this->resourceDirectory,
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