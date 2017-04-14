<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 25)->nullable();
            $table->string('name', 25)->nullable();
            $table->integer('recurring')->nullable()->unsigned();
            $table->string('icon', 100)->nullable();
            $table->string('subtype', 20)->nullable();
            $table->string('holderName', 50)->nullable();
            $table->date('date')->nullable();
            $table->integer('missed')->nullable();
            $table->integer('breed_id')->nullable()->unsigned()->index();
            $table->boolean('closed')->default(0);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
