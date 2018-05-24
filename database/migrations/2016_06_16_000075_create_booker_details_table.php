<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booker_details', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();            
            $table->bigInteger('item_user_id')->unsigned()->nullable()->index();
            $table->foreign('item_user_id')
                ->references('id')->on('item_users')
                ->onDelete('set null');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile');            
            $table->text('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booker_details');
    }
}
