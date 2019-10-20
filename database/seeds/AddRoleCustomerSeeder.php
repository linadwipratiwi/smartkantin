<?php

use Bican\Roles\Models\Role;
use Illuminate\Database\Seeder;

class AddRoleCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
            'name' => 'Customer',
            'slug' => 'customer'
        ]);
    }
}
