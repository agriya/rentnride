<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('late_payment_details', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->timestamps();            
            $table->bigInteger('item_user_id')->unsigned()->nullable()->index();
            $table->foreign('item_user_id')
                ->references('id')->on('item_users')
                ->onDelete('set null');
            $table->dateTime('booking_start_date');
            $table->dateTime('booking_end_date');
            $table->dateTime('checkin_date');
            $table->dateTime('checkout_date');
            $table->double('booking_amount', 10, 2)->default(0.00)->nullable();            
            $table->double('late_checkout_fee', 10, 2)->default(0.00)->nullable();            
            $table->bigInteger('extra_time_taken')->default(0);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('late_payment_details');
    }
}
