<?php

use Bican\Roles\Models\Role;
use Illuminate\Database\Seeder;

class AddRoleUserVendingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::create([
            'name' => 'User Vending',
            'slug' => 'user-vending'
        ]);
    }
}
