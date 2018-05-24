<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCashWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cash_withdrawals', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->bigInteger('withdrawal_status_id')->unsigned()->nullable()->index();
            $table->foreign('withdrawal_status_id')
                ->references('id')->on('withdrawal_statuses')
                ->onDelete('set null');
            $table->double('amount', 10, 2)->default(0.00);
            $table->bigInteger('money_transfer_account_id')->unsigned()->nullable()->index();
            $table->foreign('money_transfer_account_id')
                ->references('id')->on('money_transfer_accounts')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_cash_withdrawals');
    }
}
