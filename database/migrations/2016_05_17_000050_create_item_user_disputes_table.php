<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemUserDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_user_disputes', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('item_user_disputable_id');
            $table->string('item_user_disputable_type');
            $table->bigInteger('model_type');
            $table->bigInteger('dispute_type_id')->unsigned()->nullable()->index();
            $table->foreign('dispute_type_id')
                ->references('id')->on('dispute_types')
                ->onDelete('cascade');
            $table->bigInteger('dispute_status_id')->unsigned()->nullable()->index();
            $table->foreign('dispute_status_id')
                ->references('id')->on('dispute_statuses')
                ->onDelete('cascade');
            $table->bigInteger('last_replied_user_id')->unsigned()->nullable()->index();
            $table->foreign('last_replied_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('dispute_closed_type_id')->unsigned()->nullable()->index();
            $table->foreign('dispute_closed_type_id')
                ->references('id')->on('dispute_closed_types')
                ->onDelete('set null');
            $table->boolean('is_favor_booker')->nullable();
            $table->boolean('is_booker')->nullable();
            $table->dateTime('last_replied_date')->nullable();
            $table->dateTime('resolved_date')->nullable();
            $table->bigInteger('dispute_conversation_count')->default(0);
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('item_user_disputes');
    }
}
