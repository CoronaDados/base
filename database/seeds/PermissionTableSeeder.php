<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'Efetuar Login',
            'Ver Funções',
            'Cadastrar Funções',
            'Editar Funções',
            'Deletar Funções',
            'Ver Usuários',
            'Cadastrar Usuários',
            'Editar Usuários',
            'Deletar Usuários',
            'Cadastrar Colaborador',
            'Monitorar Colaborador',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['guard_name' => 'company', 'name' => $permission]);
        }

        $roleAdmin = Spatie\Permission\Models\Role::create(['guard_name' => 'company', 'name' => 'Admin']);
        $roleLider = Spatie\Permission\Models\Role::create(['guard_name' => 'company', 'name' => 'Lider']);
        $roleColaborador = Spatie\Permission\Models\Role::create(['guard_name' => 'company', 'name' => 'Colaborador']);

        $permissions = \Spatie\Permission\Models\Permission::pluck('id', 'id')->all();

        $roleAdmin->syncPermissions($permissions);
        $roleLider->givePermissionTo([
            'Efetuar Login',
            'Ver Usuários',
            'Cadastrar Usuários',
            'Editar Usuários',
            'Deletar Usuários',
            'Cadastrar Colaborador',
            'Monitorar Colaborador',
        ]);

        $usersAdmin = \App\Model\Company\CompanyUser::query()->where('is_admin', '=', 1)->get();
        foreach ($usersAdmin as $userAdmin) {
            $userAdmin->assignRole([$roleAdmin->id]);
        }
        $usersLider = \App\Model\Company\CompanyUser::query()->where('is_admin', '=', 0)->get();
        foreach ($usersLider as $userLider) {
            $userLider->assignRole([$roleLider->id]);
        }
    }
}
