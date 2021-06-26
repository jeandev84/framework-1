<?php
namespace Jan\Component\Database\Migration;


use Jan\Component\Database\Migration\Contract\MigrationInterface;


/**
 * Class Migration
 * @package Jan\Component\Database\Migration
*/
abstract class Migration implements MigrationInterface
{

       abstract public function up();
       abstract public function down();


      /**
       * @return string
       * @throws \ReflectionException
      */
      public function getVersion(): string
      {
           return $this->map()->getShortName();
      }



      /**
       * @return false|string
       * @throws \ReflectionException
      */
      public function getFilename()
      {
          return $this->map()->getFileName();
      }


      /**
       * @return \ReflectionClass
       * @throws \ReflectionException
      */
      protected function map(): \ReflectionClass
      {
          return new \ReflectionClass(get_called_class());
      }
}