<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRabbitKitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rabbit_kits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('given_id')->nullable();
            $table->string('sex')->nullable();
            $table->string('color')->nullable();
            $table->string('weight')->nullable();
            $table->float('current_weight')->nullable();
            $table->integer('litter_id')->nullable()->index();
            $table->boolean('alive')->default(1);
            $table->boolean('improved')->default(0);
            $table->boolean('survived')->default(1);
            $table->boolean('archived')->default(0);
            $table->string('image')->nullable();
            $table->string('notes')->nullable();
            $table->integer('user_id')->unsigned()->index();
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
        Schema::drop('rabbit_kits');
    }
}
