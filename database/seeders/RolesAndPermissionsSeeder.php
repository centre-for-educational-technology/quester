<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        Permission::create(['name' => 'view constructs']);
        Permission::create(['name' => 'create constructs']);
        Permission::create(['name' => 'edit constructs']);
        Permission::create(['name' => 'delete constructs']);
        Permission::create(['name' => 'view questionnaires']);
        Permission::create(['name' => 'create questionnaires']);
        Permission::create(['name' => 'edit questionnaires']);
        Permission::create(['name' => 'delete questionnaires']);
        Permission::create(['name' => 'view results']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // create roles and assign created permissions
        $teacher_role = Role::create(['name' => 'teacher']);
        $teacher_role->givePermissionTo('view constructs');
        $teacher_role->givePermissionTo('create constructs');
        $teacher_role->givePermissionTo('edit constructs');
        $teacher_role->givePermissionTo('delete constructs');
        $teacher_role->givePermissionTo('view questionnaires');
        $teacher_role->givePermissionTo('create questionnaires');
        $teacher_role->givePermissionTo('edit questionnaires');
        $teacher_role->givePermissionTo('delete questionnaires');
        $teacher_role->givePermissionTo('view results');

        $super_admin_role = Role::create(['name' => 'super-admin']);
        $super_admin_role->givePermissionTo(Permission::all());

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'test@example.com',
            'password' => Hash::make('testkasutaja'),
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Teacher User',
            'email' => 'teacher@example.com',
            'password' => Hash::make('teacherkasutaja'),
        ]);
        $user->assignRole($teacher_role);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Super-Admin User',
            'email' => 'ayly@tlu.ee',
            'password' => Hash::make('superadminkasutaja'),
        ]);
        $user->assignRole($super_admin_role);

    }
}
