<?php

use Illuminate\Database\Seeder;

class TestRabbitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\RabbitTest::class,100)->create();
    }
}
