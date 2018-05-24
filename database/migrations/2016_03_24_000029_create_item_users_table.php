<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_users', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('item_userable_id');
            $table->string('item_userable_type');
            $table->bigInteger('item_user_status_id')->unsigned()->nullable()->index();
            $table->foreign('item_user_status_id')
                ->references('id')->on('item_user_statuses')
                ->onDelete('set null');
            $table->dateTime('status_updated_at');
            $table->bigInteger('coupon_id')->unsigned()->nullable()->index();
            $table->foreign('coupon_id')
                ->references('id')->on('coupons')
                ->onDelete('set null');
            $table->bigInteger('cancellation_type_id')->unsigned()->nullable()->index();
            $table->foreign('cancellation_type_id')
                ->references('id')->on('cancellation_types')
                ->onDelete('set null');
            $table->bigInteger('quantity');
            $table->dateTime('item_booking_start_date');
            $table->dateTime('item_booking_end_date');
            $table->bigInteger('pickup_counter_location_id')->unsigned()->nullable()->index();
            $table->foreign('pickup_counter_location_id')
                ->references('id')->on('counter_locations')
				->onDelete('set null');
            $table->bigInteger('drop_counter_location_id')->unsigned()->nullable()->index();
            $table->foreign('drop_counter_location_id')
                ->references('id')->on('counter_locations')
				->onDelete('set null');
            $table->double('booking_amount', 10, 2)->default(0.00);
            $table->double('deposit_amount', 10, 2)->default(0.00)->nullable();
            $table->double('coupon_discount_amount', 10, 2)->default(0.00)->nullable();
            $table->double('special_discount_amount', 10, 2)->default(0.00)->nullable();
            $table->double('type_discount_amount', 10, 2)->default(0.00)->nullable();
            $table->double('surcharge_amount', 10, 2)->default(0.00)->nullable();
            $table->double('extra_accessory_amount', 10, 2)->default(0.00)->nullable();
            $table->double('tax_amount', 10, 2)->default(0.00)->nullable();
            $table->double('insurance_amount', 10, 2)->default(0.00)->nullable();
            $table->double('fuel_option_amount', 10, 2)->default(0.00)->nullable();
            $table->double('drop_location_differ_charges', 10, 2)->default(0.00)->nullable();
            $table->double('additional_fee', 10, 2)->default(0.00)->nullable();
            $table->double('admin_commission_amount', 10, 2)->default(0.00)->nullable();
            $table->double('host_service_amount', 10, 2)->default(0.00)->nullable();
            $table->double('cancellation_deduct_amount', 10, 2)->default(0.00)->nullable();
            $table->double('total_amount', 10, 2)->default(0.00);
            $table->string('reason_for_cancellation');
            $table->dateTime('cancellation_date');
            $table->boolean('is_payment_cleared');
            $table->boolean('is_dispute');
            $table->double('claim_request_amount', 10, 2)->default(0.00)->nullable();
            $table->double('late_fee', 10, 2)->default(0.00)->nullable();
            $table->double('paid_deposit_amount', 10, 2)->default(0.00)->nullable();
            $table->double('paid_manual_amount', 10, 2)->default(0.00)->nullable();
            $table->double('booker_amount', 10, 2)->default(0.00)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::drop('item_users');
    }
}

