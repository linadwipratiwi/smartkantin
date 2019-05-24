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
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(VendingMachineSeeder::class);
        $this->call(CustomerSeeder::class);
    }
}
