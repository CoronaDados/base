<?php

use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'razao' => 'Empresa',
            'cnpj' => '45.828.138/0001-11',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
