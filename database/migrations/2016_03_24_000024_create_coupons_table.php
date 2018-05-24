<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('couponable_id');
            $table->string('couponable_type');
            $table->bigInteger('model_type');
            $table->string('name', 100)->unique();
            $table->text('description');
            $table->double('discount', 10, 2)->default(0.00);;
            $table->bigInteger('discount_type_id')->unsigned()->nullable()->index();
            $table->foreign('discount_type_id')
                ->references('id')->on('discount_types')
                ->onDelete('set null');
            $table->bigInteger('no_of_quantity')
                ->default(0);
            $table->bigInteger('no_of_quantity_used')
                ->default(0);
            $table->date('validity_start_date')->nullable();
            $table->date('validity_end_date')->nullable();
            $table->double('maximum_discount_amount', 10, 2)
                ->default(0.00);
            $table->boolean('is_active');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('coupons');
    }
}

