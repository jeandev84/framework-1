<?php
namespace Jan\Component\FileSystem;


/**
 * Class FileCollection
 * @package Jan\Component\FileSystem
*/
class FileCollection
{
     /**
      * @var array
     */
     protected $files = [];



     /**
      * @param string $filename
      * @return $this
     */
     public function add(string $filename): FileCollection
     {
          $this->files[] = new File($filename);

          return $this;
     }



     /**
      * @param array $files
     */
     public function setFiles(array $files)
     {
         foreach ($files as $filename) {
             $this->add($filename);
         }
     }



     /**
      * @return array
     */
     public function getFiles(): array
     {
         return $this->files;
     }
}