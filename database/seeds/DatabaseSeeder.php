<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TestRabbitsTableSeeder::class);
        $this->call(RabbitBreedersTableSeeder::class);
//        $this->call(LittersTableSeeder::class);
    }
}
