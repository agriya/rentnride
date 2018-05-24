<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancellationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellation_types', function (Blueprint $table) {
            $table->bigIncrements('id')->index();;
            $table->timestamps();
            $table->string('name', 255);
            $table->text('description');
            $table->string('minimum_duration');
            $table->string('maximum_duration');
            $table->double('deduct_rate', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cancellation_types');
    }
}
