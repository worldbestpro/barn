<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLitterablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litterables', function (Blueprint $table) {
            $table->integer('litter_id')->unsigned()->nullable();
            $table->integer('litterable_id')->unsigned()->index();
            $table->string('litterable_type')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('litterables');
    }
}
