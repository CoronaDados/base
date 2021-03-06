<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([PermissionTableSeeder::class]);
        $this->call([CompaniesSeeder::class]);
        $this->call([PersonSeeder::class]);
        $this->call([CompanyUsersSeeder::class]);
        $this->call([PersonablesSeeder::class]);
        $this->call([ModelHasRolesSeeder::class]);
    }
}
