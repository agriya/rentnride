<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();
            $table->bigInteger('attachmentable_id');
            $table->string('attachmentable_type', 255);
            $table->string('filename', 255);
            $table->string('dir', 255);
            $table->string('mimetype', 255);
            $table->bigInteger('filesize');
            $table->bigInteger('height');
            $table->bigInteger('width');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attachments');
    }
}
