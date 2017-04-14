<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRabbitBreedersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rabbit_breeders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('cage')->nullable();
            $table->string('tattoo')->nullable();
            $table->string('sex')->nullable();
            $table->string('father_id')->nullable()->index();
            $table->string('mother_id')->nullable()->index();
            $table->string('color')->nullable();
            $table->float('weight')->nullable();
            $table->date('aquired')->nullable();
            $table->string('image')->nullable();
            $table->longText('notes')->nullable();
            $table->boolean('archived')->nullable()->default(0);
            $table->integer('kits')->nullable();
            $table->integer('litters_count')->nullable();
            $table->integer('user_id')->nullable()->unsigned()->index();
            $table->integer('live_kits')->nullable();
            $table->integer('survival_rate')->nullable();
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
        Schema::drop('rabbit_breeders');
    }
}
