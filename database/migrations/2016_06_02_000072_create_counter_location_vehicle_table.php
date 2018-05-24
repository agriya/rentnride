<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterLocationVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_location_vehicle', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->bigInteger('counter_location_id')->unsigned()->nullable()->index();
            $table->foreign('counter_location_id')
                ->references('id')->on('counter_locations')
				->onDelete('set null');
            $table->bigInteger('vehicle_id')->unsigned()->nullable()->index();
            $table->foreign('vehicle_id')
                ->references('id')->on('vehicles')
				->onDelete('set null');
            $table->boolean('is_pickup')->default(0)->nullable();
            $table->boolean('is_drop')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('counter_location_vehicle');
    }
}
