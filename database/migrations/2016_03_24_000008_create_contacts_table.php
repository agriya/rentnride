<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->bigInteger('ip_id')->unsigned()->nullable()->index();
            $table->foreign('ip_id')
                ->references('id')->on('ips')
                ->onDelete('set null');
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->string('telephone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
