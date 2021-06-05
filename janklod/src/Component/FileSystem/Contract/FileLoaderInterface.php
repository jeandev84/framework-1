<?php
namespace Jan\Component\FileSystem\Contract;


/**
 * Interface FileLoaderInterface
 * @package Jan\Component\FileSystem\Contract
*/
interface FileLoaderInterface
{
     /**
      * Load file
      *
      * @param string $filename
      * @return mixed
     */
     public function load(string $filename);
}