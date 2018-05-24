<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleSpecialPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_special_prices', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->date('start_date');
            $table->date('end_date');
            $table->bigInteger('vehicle_type_id')->unsigned()->nullable()->index();
            $table->foreign('vehicle_type_id')
                ->references('id')->on('vehicle_types')
                ->onDelete('cascade');
            $table->double('discount_percentage', 10, 2)->default(0.00);
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
        Schema::drop('vehicle_special_prices');
    }
}
