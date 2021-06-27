<?php
namespace Jan\Component\Database\Migration;


use Jan\Component\Database\Migration\Contract\MigrationInterface;


/**
 * Class Migration
 * @package Jan\Component\Database\Migration
*/
abstract class Migration implements MigrationInterface
{

      /**
       * @var string
      */
      protected $executedAt;



      /**
       * @return string
       * @throws \ReflectionException
      */
      public function getName(): string
      {
           return $this->checkMigration()->getShortName();
      }



      /**
       * @return false|string
       * @throws \ReflectionException
      */
      public function getFilename()
      {
          return $this->checkMigration()->getFileName();
      }


      /**
       * @param string $executedAt
      */
      public function setExecutedAt(string $executedAt)
      {
            $this->executedAt = $executedAt;
      }


      /**
        * @return string
      */
      public function getExecutedAt(): string
      {
          return $this->executedAt;
      }




      /**
       * @return \ReflectionClass
       * @throws \ReflectionException
      */
      protected function checkMigration(): \ReflectionClass
      {
          return new \ReflectionClass(get_called_class());
      }


      abstract public function up();
      abstract public function down();
}