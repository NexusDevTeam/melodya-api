<?php

namespace Database\Seeders;

use App\Enums\ActiveRoleUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'adm@melodya.com'],
            [
                'external_id' => Str::uuid()->toString(),
                'name' => 'Melodya Admin',
                'password' => bcrypt('adm@melodya'),
                'active_role' => ActiveRoleUser::SUPER_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Atribuindo roles ao usuário
        $user->assignRole([ActiveRoleUser::SUPER_ADMIN]);
    }
}
