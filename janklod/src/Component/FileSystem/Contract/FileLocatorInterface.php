<?php
namespace Jan\Component\FileSystem\Contract;


/**
 * Interface FileLocatorInterface
 * @package Jan\Component\FileSystem\Contract
*/
interface FileLocatorInterface
{
     /**
      * @param string $path
      * @return mixed
     */
     public function locate(string $path);
}