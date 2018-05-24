<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->text('about_me')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook_profile_link')->nullable();
            $table->string('twitter_profile_link')->nullable();
            $table->string('google_plus_profile_link')->nullable();
            $table->string('linkedin_profile_link')->nullable();
            $table->string('youtube_profile_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_profiles');
    }
}
