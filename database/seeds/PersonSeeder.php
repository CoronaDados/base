<?php

use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('persons')->insert([
            'name' => 'Usuário',
            'phone' => '+5548995906549',
            'cpf' => '87511937950',
            'sector' => 'Administrativo',
            'birthday' => '1992-07-19',
            'gender' => 'M',
            'status' => 1,
            'cep' => '88708725',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
