<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manageUsers = factory(App\Models\Permission::class)->create(['name' => 'manageUsers', 'display_name' => 'Manage users']);
        $adminRole   = factory(App\Models\Role::class)->create(['name' => 'admin', 'display_name' => 'Administrator']);
        factory(App\Models\Role::class)->create(['name' => 'user', 'display_name' => 'Beta user']);
        $adminRole->attachPermission($manageUsers);
    }
}
