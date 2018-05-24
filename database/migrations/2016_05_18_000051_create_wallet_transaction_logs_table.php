<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transaction_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
			$table->double('amount', 10, 2)->default(0.00);
            $table->string('wallet_transaction_logable_type');
            $table->bigInteger('wallet_transaction_logable_id')->unsigned();
            $table->string('status');
            $table->string('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wallet_transaction_logs');
    }
}
