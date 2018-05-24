<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('items', function (Blueprint $table) {
        $table->bigIncrements('id')->index();
		$table->timestamps();
		$table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
		$table->string('name');
		$table->string('slug')->unique();
		$table->text('description');
		$table->double('amount', 10, 2)->default(0.00);
		$table->boolean('is_active');
		$table->boolean('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
