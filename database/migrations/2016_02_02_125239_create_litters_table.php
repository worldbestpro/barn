<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('given_id')->nullable();
            $table->date('born')->nullable();
            $table->date('bred')->nullable();
            $table->string('notes')->nullable();
            $table->float('total_weight')->unsigned()->nullable();
            $table->float('average_weight')->unsigned()->nullable();
            $table->integer('kits_amount')->nullable();
            $table->integer('survival_rate')->unsigned()->nullable();
            $table->integer('kits_died')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->boolean('archived')->default(0);
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
        Schema::drop('litters');
    }
}
