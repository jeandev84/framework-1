<?php
namespace Jan\Component\FileSystem;


use Jan\Component\FileSystem\Exception\FileSystemException;

/**
 * Class FileSystem
 * @package Jan\Component\FileSystem
*/
class FileSystem extends FileLoader
{


    /**
     * FileSystem constructor.
     * @param string $resourceDirectory
    */
    public function __construct(string $resourceDirectory = '')
    {
          parent::__construct($resourceDirectory);
    }



    /**
     * @param string $subDirectory
     * @return array|false
    */
    public function scan(string $subDirectory)
    {
        return scandir($this->resourceSubDir($subDirectory));
    }



    /**
     * Write content into the file
     *
     * @param $filename
     * @param $content
     * @return false|int
    */
    public function write($filename, $content)
    {
        return file_put_contents($this->locate($filename), $content, FILE_APPEND);
    }



    /**
     * read file content
     *
     * @param $filename
     * @return false|string
    */
    public function read($filename)
    {
        return file_get_contents($this->locate($filename));
    }


    /**
     * copy file to other destination
     *
     * @param $from
     * @param $destination
    */
    public function copy($from, $destination)
    {
        // TODO implements
    }


    /**
     * uploading file
     *
     * @param $target
     * @param $filename
    */
    public function move($target, $filename)
    {
        // TODO implements
    }


    /**
     * @param $filename
     * @return File
    */
    public function info($filename): File
    {
        return new File($this->locate($filename));
    }


    /**
     * Create directory
     *
     * @param string $directory
     * @return bool
    */
    public function mkdir(string $directory): bool
    {
        $directory = $this->locate($directory);

        if(! \is_dir($directory)) {
            return @mkdir($directory, 0777, true);
        }

        return true;
    }


    /**
     * Create a file
     *
     * @param $filename
     * @return bool
     */
    public function make($filename): bool
    {
        $dirname = dirname($this->locate($filename));

        if(! \is_dir($dirname)) {
            @mkdir($dirname, 0777, true);
        }

        return touch($this->locate($filename));
    }


    /**
     * @param $filename
     * @param $replacements
     * @return string|string[]
    */
    public function replace($filename, $replacements)
    {
        $content = $this->read($filename);
        $replacements = array_keys($replacements);

        return str_replace($replacements, $replacements, $content);
    }




    /**
     * @param $filename
     * @return bool
    */
    public function remove($filename): bool
    {
        if(! $this->exists($filename)) {
            return false;
        }

        return unlink($this->locate($filename));
    }



    /**
     * Remove files
     *
     * @param string $maskLink
    */
    public function removeResources(string $maskLink)
    {
        if($resources = $this->resources($maskLink)) {
            array_map("unlink", $resources);
        }
    }
}