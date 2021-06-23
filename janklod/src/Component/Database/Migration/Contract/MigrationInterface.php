<?php
namespace Jan\Component\Database\Migration\Contract;


/**
 * Interface MigrationInterface
 *
 * @package Jan\Component\Database\Migration\Contract
*/
interface MigrationInterface
{

    /**
     * @return mixed
    */
    public function up();


    /**
     * @return mixed
    */
    public function down();
}