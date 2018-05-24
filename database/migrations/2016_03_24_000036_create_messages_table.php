<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('to_user_id')->unsigned()->nullable()->index();
            $table->foreign('to_user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('message_id')->unsigned()->nullable()->index();
            $table->foreign('message_id')
                ->references('id')->on('messages')
                ->onDelete('cascade');
			$table->bigInteger('message_content_id')->unsigned()->nullable()->index();
            $table->foreign('message_content_id')
                ->references('id')->on('message_contents')
                ->onDelete('set null');
            $table->bigInteger('message_folder_id');
            $table->bigInteger('messageable_id');
            $table->bigInteger('item_user_status_id')->unsigned()->nullable()->index();
			$table->foreign('item_user_status_id')
                ->references('id')->on('item_user_statuses')
                ->onDelete('set null');
            $table->bigInteger('dispute_status_id')->unsigned()->nullable()->index();
			$table->foreign('dispute_status_id')
                ->references('id')->on('dispute_statuses')
                ->onDelete('set null');
            $table->string('messageable_type');
            $table->boolean('is_sender');
            $table->boolean('is_starred');
            $table->boolean('is_read');
            $table->boolean('is_deleted');
            $table->boolean('is_archived');
            $table->boolean('is_review');
            $table->boolean('is_communication');
            $table->string('hash');
            $table->bigInteger('size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
