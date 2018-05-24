<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSudopayTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("sudopay_transaction_logs", function(Blueprint $table){
           $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->double('amount', 10, 2)->default(0.00);
            $table->bigInteger('payment_id');
            $table->bigInteger('sudopay_transaction_logable_id');
            $table->string('sudopay_transaction_logable_type');
            $table->string('sudopay_pay_key');
            $table->bigInteger('merchant_id');
            $table->bigInteger('gateway_id');
            $table->string('gateway_name');
            $table->string('status');
            $table->string('payment_type');
            $table->bigInteger('buyer_id');
            $table->string('buyer_email');
            $table->string('buyer_address');
            $table->double('sudopay_transaction_fee', 10,2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sudopay_transaction_logs');
    }
}
