<?php
namespace Jan\Component\FileSystem\Contract;


/**
 * Interface FileLocatorInterface
 * @package Jan\Component\FileSystem\Contract
*/
interface FileLocatorInterface
{
     /**
      * @param string $filename
      * @return mixed
     */
     public function locate(string $filename);
}