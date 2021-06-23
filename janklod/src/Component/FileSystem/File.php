<?php
namespace Jan\Component\FileSystem;


/**
 * Class File
 * @package Jan\Component\FileSystem
*/
class File
{


    /**
     * Directory name of file
     *
     * @var string
    */
    protected $dirname;



    /**
     * Base name of file
     *
     * @var string
    */
    protected $basename;

    /**
     *  Name of file
     *
     * @var string
    */
    protected $filename;


    /**
     * Extension of file
     *
     * @var string
    */
    protected $extension;


    /**
     * File constructor.
     *
     * @param string $path
    */
    public function __construct(string $path)
    {
        $this->dirname   = pathinfo($path, PATHINFO_DIRNAME);
        $this->basename  = pathinfo($path, PATHINFO_BASENAME);
        $this->filename  = pathinfo($path, PATHINFO_FILENAME);
        $this->extension = pathinfo($path, PATHINFO_EXTENSION);
    }


    /**
     * get file dirname
     *
     * @return string
    */
    public function getDirname(): string
    {
        return $this->dirname;
    }




    /**
     * get base name
     *
     * @return string
    */
    public function getBasename(): string
    {
        return $this->basename;
    }


    /**
     * get name of file
     *
     * @return string
    */
    public function getFilename(): string
    {
        return $this->filename;
    }


    /**
     * get file extension
     *
     * @return string|null
    */
    public function getExtension(): ?string
    {
        return $this->extension;
    }
}