<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisputeClosedTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispute_closed_types', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->timestamps();            
            $table->string('name');
            $table->bigInteger('dispute_type_id')->unsigned()->nullable()->index();
            $table->foreign('dispute_type_id')
                ->references('id')->on('dispute_types')
                ->onDelete('cascade');
            $table->boolean('is_booker')->default(0);
            $table->string('resolved_type');
            $table->string('reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dispute_closed_types');
    }
}
