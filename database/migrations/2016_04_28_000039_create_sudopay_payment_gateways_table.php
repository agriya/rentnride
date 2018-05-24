<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSudopayPaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("sudopay_payment_gateways", function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->string('sudopay_gateway_name');
            $table->text('sudopay_gateway_details');
            $table->boolean('is_marketplace_supported')->default(0);
            $table->bigInteger('sudopay_gateway_id');
            $table->bigInteger('sudopay_payment_group_id')->unsigned()->nullable()->index();
            $table->foreign('sudopay_payment_group_id')
                ->references('id')->on('sudopay_payment_groups')
                ->onDelete('set null');
            $table->text('form_fields_credit_card');
            $table->text('form_fields_manual');
            $table->text('form_fields_buyer');
            $table->string('thumb_url');
            $table->text('supported_features_actions');
            $table->text('supported_features_card_types');
            $table->text('supported_features_countries');
            $table->text('supported_features_credit_card_types');
            $table->text('supported_features_currencies');
            $table->text('supported_features_languages');
            $table->text('supported_features_services');
            $table->text('connect_instruction');
            $table->string('name');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sudopay_payment_gateways');
    }
}
