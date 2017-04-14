<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBreederIdToPedigree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedigrees', function (Blueprint $table) {
            //
            $table->string('rabbit_breeders_id')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedigrees', function (Blueprint $table) {
            //
            $table->dropColumn('rabbit_breeders_id');
        });
    }
}
