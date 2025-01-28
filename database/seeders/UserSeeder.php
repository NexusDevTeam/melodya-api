<?php

namespace Database\Seeders;

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
            ['auth0_id' => 'auth0|67993cf23046dc16f76a90cb'],
            [
                'external_id' => Str::uuid()->toString(),
                'auth0_id' => 'auth0|67993cf23046dc16f76a90cb',
                'auth0_user_id' => '67993cf23046dc16f76a90cb',
                'auth0_provider' => 'auth0',
                'name' => 'Melodya SA',
                'email' => 'devnexusorg@gmail.com',
                'avatar_auth0_url' => 'https://s.gravatar.com/avatar/b47d9b271d408d17a410f8d0a5046130?s=480&r=pg&d=https%3A%2F%2Fcdn.auth0.com%2Favatars%2Fde.png',
                'avatar_url' => 'https://s.gravatar.com/avatar/b47d9b271d408d17a410f8d0a5046130?s=480&r=pg&d=https%3A%2F%2Fcdn.auth0.com%2Favatars%2Fde.png',
                'email_verified' => true,
                'is_social' => false,
            ]
        );

        // Atribuindo roles ao usuário
        $user->assignRole(['super_admin']);
    }
}
