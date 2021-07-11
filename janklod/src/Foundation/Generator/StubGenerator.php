<?php
namespace Jan\Foundation\Generator;


use Jan\Component\FileSystem\FileSystem;



/**
 * Class StubGenerator
 * @package Jan\Foundation\Generator
*/
class StubGenerator
{

       /**
        * @var FileSystem
       */
       protected $fileSystem;



       /**
        * StubGenerator constructor.
        * @param FileSystem $fileSystem
       */
       public function __construct(FileSystem $fileSystem)
       {
            $this->fileSystem = $fileSystem;
       }


       public function generateStub()
       {
           // TODO implements
       }
}