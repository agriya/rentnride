<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTypeSurchargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_type_surcharges', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->bigInteger('vehicle_type_id')->unsigned()->index();
            $table->foreign('vehicle_type_id')
                ->references('id')->on('vehicle_types')
				->onDelete('cascade');
            $table->bigInteger('surcharge_id')->unsigned()->index();
            $table->foreign('surcharge_id')
                ->references('id')->on('surcharges')
				->onDelete('cascade');
            $table->double('rate', 10, 2)->default(0.00);
            $table->bigInteger('discount_type_id')->unsigned()->nullable()->index();
            $table->foreign('discount_type_id')
                ->references('id')->on('discount_types')
				->onDelete('set null');
            $table->bigInteger('duration_type_id')->unsigned()->nullable()->index();
            $table->foreign('duration_type_id')
                ->references('id')->on('duration_types')
				->onDelete('set null');
            $table->double('max_allowed_amount', 10, 2)->default(0.00)->nullable();
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
        Schema::drop('vehicle_type_surcharges');
    }
}
