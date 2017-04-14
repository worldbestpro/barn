<?php

use Illuminate\Database\Seeder;

class RabbitBreedersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\RabbitBreeder::class,10)->create();
    }
}
