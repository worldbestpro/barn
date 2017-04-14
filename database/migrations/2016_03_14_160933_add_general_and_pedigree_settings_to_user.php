<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeneralAndPedigreeSettingsToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('general_weight_units')->after('remember_token');
            $table->integer('pedigree_number_generations')->after('remember_token');
            $table->text('pedigree_rabbitry_information')->after('remember_token');
            $table->string('pedigree_logo')->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('general_weight_units');
            $table->dropColumn('pedigree_number_generations');
            $table->dropColumn('pedigree_rabbitry_information');
            $table->dropColumn('pedigree_logo');
        });
    }
}
