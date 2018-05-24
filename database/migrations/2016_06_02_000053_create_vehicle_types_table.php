<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->string('name')->index();
            $table->string('slug');
            $table->double('minimum_hour_price', 10, 2)->default(0.00)->nullable();
            $table->double('maximum_hour_price', 10, 2)->default(0.00)->nullable();
            $table->double('minimum_day_price', 10, 2)->default(0.00)->nullable();
            $table->double('maximum_day_price', 10, 2)->default(0.00)->nullable();
            $table->double('drop_location_differ_unit_price', 10, 2)->default(0.00)->nullable();
            $table->double('drop_location_differ_additional_fee', 10, 2)->default(0.00)->nullable();
            $table->double('deposit_amount', 10, 2)->default(0.00)->nullable();
            $table->bigInteger('vehicle_count')->default(0)->nullable();
            $table->double('late_checkout_addtional_fee', 10, 2)->default(0.00)->nullable();            
			$table->bigInteger('duration_type_id')->unsigned()->index()->nullable();
            $table->foreign('duration_type_id')
                ->references('id')->on('duration_types')
                ->onDelete('set null');
            $table->boolean('is_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vehicle_types');
    }
}
