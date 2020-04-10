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
            'name' => 'Teste',
            'email' => 'teste@empresa.com',
            'email_verified_at' => now(),
            'is_admin' => 1,
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
