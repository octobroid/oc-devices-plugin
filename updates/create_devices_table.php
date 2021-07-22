<?php namespace Octobro\Devices\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('octobro_devices_devices', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('uuid')->nullable()->index();
            $table->string('push_token')->nullable()->index();
            $table->string('platform')->nullable();
            $table->string('version')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octobro_devices_devices');
    }
}
