<?php
namespace Jan\Component\FileSystem;


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
    public function localise(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->resourceDirectory, $this->resolvePath($filename)]);
    }



    /**
     * Determine if the given file exist
     *
     * @param string $filename
     * @return bool
    */
    public function exists(string $filename): bool
    {
        return file_exists($this->localise($filename));
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