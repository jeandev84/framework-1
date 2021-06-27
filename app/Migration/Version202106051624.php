<?php
namespace App\Migration;


use Jan\Component\Database\Migration\Migration;
use Jan\Component\Database\Schema\BluePrint;

/**
 * Class Version202106051624
 *
 * @package App\Migration
*/
class Version202106051624 extends Migration
{

    public function up()
    {
        $this->schema->create('posts', function (BluePrint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->text('content');
            $table->boolean('published');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('posts');
    }
}