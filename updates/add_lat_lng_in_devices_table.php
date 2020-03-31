<?php namespace Octobro\Devices\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddLatitudeLongitudeInDevicesTable extends Migration
{
    public function up()
    {
        Schema::table('octobro_devices_devices', function(Blueprint $table) {
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
    }

    public function down()
    {
        Schema::table('octobro_devices_devices', function(Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
