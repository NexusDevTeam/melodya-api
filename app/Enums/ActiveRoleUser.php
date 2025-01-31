<?php

namespace App\Enums;

enum ActiveRoleUser: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case ARTIST = 'artist';
    case CLIENT = 'client';

    public function label(): string
    {
        return match ($this) {
            ActiveRoleUser::SUPER_ADMIN => 'Super Administrador',
            ActiveRoleUser::ADMIN => 'Administrador',
            ActiveRoleUser::ARTIST => 'Artista',
            ActiveRoleUser::CLIENT => 'Cliente',
        };
    }

    public static function admin($activeRole): bool
    {
        return in_array($activeRole, [ActiveRoleUser::SUPER_ADMIN->value, ActiveRoleUser::ADMIN->value]);
    }
}
