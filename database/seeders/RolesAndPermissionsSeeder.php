<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create constructs']);
        Permission::create(['name' => 'edit constructs']);
        Permission::create(['name' => 'delete constructs']);
        Permission::create(['name' => 'create questionnaires']);
        Permission::create(['name' => 'edit questionnaires']);
        Permission::create(['name' => 'delete questionnaires']);
        Permission::create(['name' => 'view results']);

        // create roles and assign created permissions
        $role = Role::create(['name' => 'teacher']);
        $role->givePermissionTo('create constructs');
        $role->givePermissionTo('edit constructs');
        $role->givePermissionTo('delete constructs');
        $role->givePermissionTo('create questionnaires');
        $role->givePermissionTo('edit questionnaires');
        $role->givePermissionTo('delete questionnaires');

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
