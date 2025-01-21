<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['role_auth0_id' => 'rol_ruTdJmZpU3vEycii'],
            [
                'role_name' => 'Admin',
                'role_auth0_id' => 'rol_ruTdJmZpU3vEycii',
            ]);

        Role::updateOrCreate(
            ['role_auth0_id' => 'rol_JL24FOp0RwkdgJ9e'],
            [
                'role_name' => 'Artist',
                'role_auth0_id' => 'rol_JL24FOp0RwkdgJ9e',
            ]);

        Role::updateOrCreate(
            ['role_auth0_id' => 'rol_jNPOsJf09g1pU2H7'],
            [
                'role_name' => 'Client',
                'role_auth0_id' => 'rol_jNPOsJf09g1pU2H7',
            ]);
    }
}
