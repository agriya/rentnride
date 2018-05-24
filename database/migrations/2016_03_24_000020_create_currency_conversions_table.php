<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyConversionsTable extends Migration
{
    /**
     *  Run the migrations.
     */
    public function up()
    {
        Schema::create('currency_conversions', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('currency_id')->unsigned()->nullable()->index();
            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('cascade');
            $table->bigInteger('converted_currency_id')->unsigned()->nullable()->index();
            $table->foreign('converted_currency_id')
                ->references('id')->on('currencies')
                ->onDelete('cascade');
            $table->double('rate', 10, 6)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('currency_conversions');
    }
}
