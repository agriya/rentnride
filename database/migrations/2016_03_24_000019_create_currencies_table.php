<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->string('name');
            $table->string('code', 10);
            $table->string('symbol');
            $table->string('prefix', 10);
            $table->string('suffix', 10);
            $table->string('decimals', 10);
            $table->char('dec_point', 2);
            $table->char('thousands_sep', 2);
            $table->boolean('is_prefix_display_on_left');
            $table->boolean('is_use_graphic_symbol');
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
        Schema::drop('currencies');
    }
}
