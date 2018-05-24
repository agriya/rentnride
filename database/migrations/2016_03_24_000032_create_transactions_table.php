<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('receiver_user_id')->unsigned()->nullable()->index();
            $table->foreign('receiver_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('transactionable_id');
            $table->string('transactionable_type');
            $table->bigInteger('transaction_type_id')->unsigned()->nullable()->index();
            $table->foreign('transaction_type_id')
                ->references('id')->on('transaction_types')
                ->onDelete('set null');
            $table->double('amount', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->bigInteger('payment_gateway_id')->unsigned()->nullable()->index();
            $table->double('gateway_fees', 10, 2)->default(0.00)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
