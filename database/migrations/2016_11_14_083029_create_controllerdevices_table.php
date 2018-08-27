<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControllerDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controller_devices', function(Blueprint $table){
          $table->increments('iid');
          $table->string('name');
          $table->ipAddress('ip');
          $table->string('port');
          $table->macAddress('mac');
          $table->unsignedInteger('doorstrike')->nullable();
          $table->string('device');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('controller_devices');
    }
}
