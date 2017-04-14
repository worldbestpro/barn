<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedigreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedigrees', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('rabbit_breeder_id')->unsigned()->nullable();
            $table->foreign('rabbit_breeder_id')
                ->references('id')->on('rabbit_breeders')
                ->onDelete('cascade');
            $table->string('level')->nullable();

            $table->string('name')->nullable();
            $table->string('custom_id')->nullable();
            $table->date('day_of_birth')->nullable();
            $table->string('breed')->nullable();
            $table->string('sex')->nullable();
            $table->string('image')->nullable();
            $table->text('notes')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedigrees');
    }
}
