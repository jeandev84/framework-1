<?php
namespace Jan\Component\Http\File;




/**
 * Class UploadedFile
 *
 * @package Jan\Component\Http\File
*/
class UploadedFile extends File
{

    /**
     * @var string
    */
    protected $originalName;



    /**
     * @var string
    */
    protected $mimeType;



    /**
     * @var string
    */
    protected $tempFile;



    /**
     * @var int
    */
    protected $error;




    /**
     * @var int
    */
    protected $size;





    /**
     * UploadedFile constructor.
     * @param string $originalName
     * @param string $mimeType
     * @param string $tempFile
     * @param string $error
     * @param int $size
     */
     public function __construct(string $originalName, string $mimeType, string $tempFile, string $error, int $size)
     {
         $this->originalName = $originalName;
         $this->mimeType = $mimeType;
         $this->tempFile = $tempFile;
         $this->error = $error;
         $this->size = $size;
     }



     public function move(string $target, string $newFilename = null)
     {
           dd('MOVED');
     }
}