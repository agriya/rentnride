<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('provider_users', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->bigInteger('provider_id')->unsigned()->nullable()->index();
            $table->foreign('provider_id')
                ->references('id')->on('providers')
                ->onDelete('cascade');
            $table->string('access_token');
            $table->string('access_token_secret');
            $table->bigInteger('foreign_id');
            $table->boolean('is_connected');
            $table->string('profile_picture_url');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('provider_users');
    }
}
