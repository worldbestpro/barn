<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class)->create([
            'name'=>'admin',
            'email'=>'admin@mail.com',
            'password'=>bcrypt('123123')])->attachRole(1);

        factory(\App\Models\User::class)->create([
            'name'=>'beta user',
            'email'=>'beta@mail.com',
            'password'=>bcrypt('123123')])->attachRole(2);

        factory(\App\Models\User::class)->create([
            'name'=>'Scott',
            'email'=>'scotthaas@live.com',
            'password'=>bcrypt('Haas88Q')])->attachRole(1);
    }
}
