<?php
namespace App\Migration;


use Jan\Component\Database\Migration\Migration;
use Jan\Component\Database\Schema\BluePrint;

/**
 * Class Version202106051623
 *
 * @package App\Migration
*/
class Version202106051623 extends Migration
{

    public function up()
    {
        $this->schema->create('users', function (BluePrint $table) {
            $table->increments('id');
            $table->string('email', 200);
            $table->string('password', 300);

            /*
            $table->string('surname')->nullable();
            $table->string('name')->nullable();
            $table->string('patronymic')->nullable();
            $table->boolean('enabled');
            $table->softDeletes();
            */

            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('users');
    }
}