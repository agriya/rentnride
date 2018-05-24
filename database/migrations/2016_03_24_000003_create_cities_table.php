<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->string('name');
            $table->bigInteger('state_id')->unsigned()->nullable()->index();
            $table->foreign('state_id')
                ->references('id')->on('states')
                ->onDelete('set null');
            $table->bigInteger('country_id')->unsigned()->nullable()->index();
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('set null');
            $table->double('latitude');
            $table->double('longitude');
            $table->boolean('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cities');
    }
}
