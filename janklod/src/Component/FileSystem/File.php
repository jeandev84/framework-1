<?php
namespace Jan\Component\FileSystem;


/**
 * Class File
 * @package Jan\Component\FileSystem
*/
class File
{


    /**
     * File path
     *
     * @var string
    */
    protected $path;



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
    public function __construct(string $path = '')
    {
         if ($path) {
             $this->setPath($path);
         }
    }



    /**
     * @return string
    */
    public function getBasename(): string
    {
        return $this->basename;
    }


    /**
     * @return string
    */
    public function getFilename(): string
    {
        return $this->filename;
    }


    /**
     * @return string|null
    */
    public function getExtension(): ?string
    {
        return $this->extension;
    }


    /**
     * @param string $path
     * @return File
    */
    protected function setPath(string $path): File
    {
        $this->path      = $path;
        $this->dirname   = pathinfo($this->path, PATHINFO_DIRNAME);
        $this->basename  = pathinfo($this->path, PATHINFO_BASENAME);
        $this->filename  = pathinfo($this->path, PATHINFO_FILENAME);
        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION);

        return $this;
    }


    /**
     * @return array
    */
    public function details(): array
    {
        return pathinfo($this->path);
    }


    /**
     * @return string
    */
    public function getPath(): string
    {
        return $this->path;
    }
}