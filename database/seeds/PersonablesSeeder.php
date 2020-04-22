<?php

use App\Model\Company\CompanyUser;
use Illuminate\Database\Seeder;

class PersonablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personables')->insert([
            'person_id' => 1,
            'personable_id' => 1,
            'personable_type' => CompanyUser::class,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
