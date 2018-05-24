<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->string('name')->index();
            $table->string('slug');
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('vehicle_company_id')->unsigned()->index()->nullable();
            $table->foreign('vehicle_company_id')
                ->references('id')->on('vehicle_companies')
                ->onDelete('set null');
            $table->bigInteger('vehicle_make_id')->unsigned()->index()->nullable();
            $table->foreign('vehicle_make_id')
                ->references('id')->on('vehicle_makes')
                ->onDelete('set null');
            $table->bigInteger('vehicle_model_id')->unsigned()->index()->nullable();
            $table->foreign('vehicle_model_id')
                ->references('id')->on('vehicle_models')
                ->onDelete('set null');
            $table->bigInteger('vehicle_type_id')->unsigned()->index()->nullable();
            $table->foreign('vehicle_type_id')
                ->references('id')->on('vehicle_types')
                ->onDelete('set null');
            $table->double('driven_kilometer', 10, 2)->default(0.00)->nullable();
            $table->string('vehicle_no')->nullable();
            $table->bigInteger('no_of_seats')->default(0)->nullable();
            $table->bigInteger('no_of_doors')->default(0)->nullable();
            $table->bigInteger('no_of_gears')->default(0)->nullable();
            $table->boolean('is_manual_transmission')->default(0)->nullable();
            $table->bigInteger('no_small_bags')->default(0)->nullable();
            $table->bigInteger('no_large_bags')->default(0)->nullable();
            $table->boolean('is_ac')->default(0)->nullable();
            $table->bigInteger('minimum_age_of_driver')->default(0)->nullable();
            $table->double('mileage', 10, 2)->default(0.00)->nullable();
            $table->boolean('is_km')->default(0)->nullable();
            $table->boolean('is_airbag')->default(0)->nullable();
            $table->bigInteger('no_of_airbags')->default(0)->nullable();
            $table->boolean('is_abs')->default(0)->nullable();
            $table->double('per_hour_amount', 10, 2)->default(0.00)->nullable();
            $table->double('per_day_amount', 10, 2)->default(0.00)->nullable();
            $table->bigInteger('fuel_type_id')->unsigned()->index()->nullable();
            $table->foreign('fuel_type_id')
                ->references('id')->on('fuel_types')
                ->onDelete('set null');
            $table->bigInteger('vehicle_rental_count')->default(0)->nullable();
            $table->bigInteger('feedback_count')->default(0)->nullable();
            $table->double('feedback_rating', 10, 2)->default(0.00)->nullable();
            $table->boolean('is_paid')->default(0);
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
        Schema::drop('vehicles');
    }
}
