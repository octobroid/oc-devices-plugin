<?php namespace Octobro\Devices\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddAdditionalColumnsOnDevicesTable extends Migration
{
    public function up()
    {
        Schema::table('octobro_devices_devices', function(Blueprint $table) {
            $table->string('platform_version')->nullable();
            $table->string('name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('octobro_devices_devices', function(Blueprint $table) {
            $table->dropColumn('platform_version');
            $table->dropColumn('name');
        });
    }
}
