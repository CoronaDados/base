<?php

use App\Model\Company\CompanyUser;
use Illuminate\Database\Seeder;

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => CompanyUser::class,
            'model_id' => 1,
        ]);
    }
}
