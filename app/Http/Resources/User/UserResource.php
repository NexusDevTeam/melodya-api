<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var null
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatar_url = $this->resource->avatar_url ? env('AWS_URL') . $this->resource->avatar_url : null;

        return [
            ...$this->resource->toArray(),
            'avatar_url' => $avatar_url,
            'roles' => $this->rolesAndPermissions(),
        ];
    }

    /**
     * Get the roles and permissions of the user.
     *
     * @return array<string, mixed>
     */
    private function rolesAndPermissions(): array
    {
        $roles = $this->resource->roles->mapWithKeys(function ($role) {
            return [
                $role->name => [
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return $permission->name;
                    }),
                ],
            ];
        });

        return $roles->toArray();
    }
}
