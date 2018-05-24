<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnavailableVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unavailable_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();            
            $table->bigInteger('item_user_id')->unsigned()->nullable()->index();
            $table->foreign('item_user_id')
                ->references('id')->on('item_users')
                ->onDelete('set null');
			$table->bigInteger('vehicle_id')->unsigned()->nullable()->index();
            $table->foreign('vehicle_id')
                ->references('id')->on('vehicles')
                ->onDelete('set null');
            $table->dateTime('start_date')->index();
            $table->dateTime('end_date')->index();
            $table->boolean('is_dummy')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('unavailable_vehicles');
    }
}
