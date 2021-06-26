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
}