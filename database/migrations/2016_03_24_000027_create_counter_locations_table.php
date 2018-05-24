<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_locations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->string('address')->index();
            $table->double('latitude', 10, 6)->default(0.00)->nullable();
            $table->double('longitude', 10, 6)->default(0.00)->nullable();
            $table->string('fax')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->index();
            $table->string('email')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('counter_locations');
    }
}
