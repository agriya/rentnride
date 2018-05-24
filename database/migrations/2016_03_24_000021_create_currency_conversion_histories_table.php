<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyConversionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('currency_conversion_histories', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('currency_conversion_id')->unsigned()->nullable()->index();
            $table->foreign('currency_conversion_id')
                ->references('id')->on('currency_conversions')
                ->onDelete('cascade');
            $table->double('rate_before_change', 10, 2)->default(0.00);
            $table->double('rate', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('currency_conversion_histories');
    }
}
