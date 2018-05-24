<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemUserAdditionalChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_user_additional_charges', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();
            $table->bigInteger('item_user_id')->unsigned()->nullable()->index();
            $table->foreign('item_user_id')
                ->references('id')->on('item_users')
				->onDelete('set null');
            $table->string('item_user_additional_chargable_type');
            $table->bigInteger('item_user_additional_chargable_id')->unsigned();
            $table->double('amount', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('item_user_additional_charges');
    }
}
