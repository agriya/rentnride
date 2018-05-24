<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddWalletAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		 Schema::create("user_add_wallet_amounts", function(Blueprint $table){
           $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->text('description');
            $table->double('amount', 10, 2)->default(0.00);
            $table->bigInteger('payment_gateway_id');
            $table->string('user_paypal_connection_id');
            $table->string('payment_id');
            $table->string('pay_key');
            $table->bigInteger('sudopay_gateway_id');
            $table->double('sudopay_revised_amount', 10, 2)->default(0.00);
            $table->string('sudopay_token');
            $table->boolean('is_success');
        });		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_add_wallet_amounts');
    }
}
