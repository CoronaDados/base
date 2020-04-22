<?php

use Illuminate\Database\Seeder;

class CompanyUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_users')->insert([
            'company_id' => 1,
            'person_id' => 1,
            'email' => 'usuario@empresa.com',
            'is_admin' => 1,
            'password' => Hash::make('secret'),
            'force_new_password' => 0,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
