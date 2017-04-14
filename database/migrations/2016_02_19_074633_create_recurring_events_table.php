<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_events', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('closed')->default(0);
            $table->integer('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')
                                       ->onDelete('cascade');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recurring_events');
    }
}
