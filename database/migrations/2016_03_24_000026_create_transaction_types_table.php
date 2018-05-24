<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->string('name');
            $table->bigInteger('transaction_type_group_id');
            $table->boolean('is_credit');
            $table->boolean('is_credit_to_receiver');
            $table->boolean('is_credit_to_admin');
            $table->text('message');
            $table->string('message_for_receiver');
            $table->string('message_for_admin');
            $table->string('transaction_variables');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transaction_types');
    }
}
