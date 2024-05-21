<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions:
        Permission::factory()->create(['name' => 'create players']);
        Permission::factory()->create(['name' => 'edit players']);
        Permission::factory()->create(['name' => 'delete players']);


        // Create roles and assign created permissions:
        // this can be done as separate statements
        $role = Role::factory()->create(['name' => 'player']);
        $role->givePermissionTo('create players', 'edit players');

        // or may be done by chaining
        $role = Role::factory()->create(['name' => 'moderator'])
            ->givePermissionTo(['create players', 'edit players', 'delete players']);

        $role = Role::factory()->create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
