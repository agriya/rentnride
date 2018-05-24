<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_contents', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->text('subject');
            $table->text('message');
            $table->boolean('admin_suspend');
            $table->boolean('is_system_flagged');
            $table->text('detected_suspicious_words');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('message_contents');
    }
}
