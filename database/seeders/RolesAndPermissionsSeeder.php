<?php

namespace Database\Seeders;

use App\Enums\ActiveRoleUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createRoleWithPermissions(ActiveRoleUser::SUPER_ADMIN->value, ActiveRoleUser::SUPER_ADMIN->label(), [
            'user' => ['list', 'create', 'edit', 'delete'],
            'permission' => ['list', 'create', 'edit', 'delete'],
            'role' => ['list', 'create', 'edit', 'delete'],
            'artist' => ['list', 'create', 'edit', 'delete'],
        ]);

        $this->createRoleWithPermissions(ActiveRoleUser::ADMIN->value, ActiveRoleUser::ADMIN->label(), [
            'user' => ['list', 'create', 'edit', 'delete'],
            'artist' => ['list', 'create', 'edit', 'delete'],
        ]);

        $this->createRoleWithPermissions(ActiveRoleUser::ARTIST->value, ActiveRoleUser::ARTIST->label(), [
            'artist' => ['list', 'create', 'edit', 'delete'],
        ]);

        $this->createRoleWithPermissions(ActiveRoleUser::CLIENT->value, ActiveRoleUser::CLIENT->label(), [
            'artist' => ['list'],
        ]);
    }

    protected function createRoleWithPermissions(string $roleName, string $titleName, array $permissions)
    {
        $role = Role::updateOrCreate(['name' => $roleName], ['title' => $titleName]);

        $permissionModels = collect();

        foreach ($permissions as $model => $actions) {
            $newPermissionModels = collect($actions)->map(function ($action) use ($model) {
                return Permission::updateOrCreate(['name' => "{$model}_{$action}"]);
            });

            $permissionModels = $permissionModels->merge($newPermissionModels);
        }

        $role->syncPermissions($permissionModels->pluck('name')->toArray());
    }
}
