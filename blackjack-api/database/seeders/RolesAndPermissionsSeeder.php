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
        // PLAYERS
        Permission::factory()->create(['name' => 'edit nickname']);
        Permission::factory()->create(['name' => 'play game']);
        Permission::factory()->create(['name' => 'delete own game history']);
        Permission::factory()->create(['name' => 'view own details']);
        Permission::factory()->create(['name' => 'delete user']);

        // MODERATORS
        Permission::factory()->create(['name' => 'view players']);

        // Create roles and assign created permissions:
        // this can be done as separate statements
        $role = Role::factory()->create(['name' => 'player']);
        $role->givePermissionTo(['edit nickname', 'play game', 'delete own game history', 'view own details']);

        // or may be done by chaining
        $role = Role::factory()->create(['name' => 'moderator'])
            ->givePermissionTo(['view players']);
        
        // SUPER-ADMIN
        $role = Role::factory()->create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
        // Super-admin can't play the game:
        $role->revokePermissionTo('play game');
    }
}
