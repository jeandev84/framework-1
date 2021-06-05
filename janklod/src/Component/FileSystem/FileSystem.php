<?php
namespace Jan\Component\FileSystem;


/**
 * Class FileSystem
 * @package Jan\Component\FileSystem
*/
class FileSystem extends FileLoader
{

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
        return file_put_contents($this->localise($filename), $content, FILE_APPEND);
    }



    /**
     * read file content
     *
     * @param $filename
     * @return false|string
    */
    public function read($filename)
    {
        return file_get_contents($this->localise($filename));
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
        return new File($this->localise($filename));
    }


    /**
     * Create directory
     *
     * @param $directory
     * @return bool
     */
    public function mkdir($directory): bool
    {
        $directory = $this->localise($directory);

        if(! is_dir($directory))
        {
            return @mkdir($directory, 0777, true);
        }
    }


    /**
     * Create a file
     *
     * @param $filename
     * @return bool
     */
    public function make($filename): bool
    {
        $dirname = dirname($this->localise($filename));

        if(! \is_dir($dirname))
        {
            @mkdir($dirname, 0777, true);
        }

        return touch($this->localise($filename));
    }


    /**
     * @param $filename
     * @param $replacements
     * @return string|string[]
     */
    public function replace($filename, $replacements)
    {
        $content = $this->read($filename);
        return str_replace(array_keys($replacements), $replacements, $content);
    }




    /**
     * @param $filename
     * @return array|false
     */
    public function unlink($filename)
    {
        return $this->exists($filename) ? unlink($filename) : false;
    }


    /**
     * Remove files
     *
     * @param $maskLink
     */
    public function remove($maskLink)
    {
        array_map("unlink", $this->resources($maskLink));
    }
}