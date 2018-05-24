<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_companies', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->string('name')->index();
            $table->string('slug');
            $table->string('address')->index();
            $table->double('latitude', 10, 6)->default(0.00)->nullable();
            $table->double('longitude', 10, 6)->default(0.00)->nullable();
            $table->string('fax')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->index();
            $table->string('email')->index();
            $table->bigInteger('vehicle_count')->default(0)->nullable();
            $table->tinyInteger('is_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vehicle_companies');
    }
}
