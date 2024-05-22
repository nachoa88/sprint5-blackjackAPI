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
        // USERS
        Permission::factory()->create(['name' => 'register players']);
        Permission::factory()->create(['name' => 'edit nickname']);
        Permission::factory()->create(['name' => 'play game']);
        Permission::factory()->create(['name' => 'view own game history']);
        Permission::factory()->create(['name' => 'delete own game history']);
        Permission::factory()->create(['name' => 'view own game details']);
        Permission::factory()->create(['name' => 'view own win averages']);

        // MODERATORS
        Permission::factory()->create(['name' => 'view players']);
        Permission::factory()->create(['name' => 'view one player win average']);
        Permission::factory()->create(['name' => 'view players win averages']);

        // Create roles and assign created permissions:
        // this can be done as separate statements
        $role = Role::factory()->create(['name' => 'player']);
        $role->givePermissionTo(['register players', 'edit nickname', 'play game', 'view own game history', 'delete own game history', 'view own game details', 'view own win averages']);

        // or may be done by chaining
        $role = Role::factory()->create(['name' => 'moderator'])
            ->givePermissionTo(['view players', 'view one player win average', 'view players win averages']);
            
        $role = Role::factory()->create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
