<?php

namespace App\Repositories\Api\V1\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserListResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class UserRepository extends BaseRepository
{
    protected $model = User::class;

    public function all($queryParams)
    {
        $perPage = $queryParams['per_page'] ?? 25;
        $users = $this->model
            ->when(isset($queryParams['q']), function ($query) use ($queryParams) {
                $query->where('name', 'like', '%' . $queryParams['q'] . '%')
                    ->orWhere('email', 'like', '%' . $queryParams['q'] . '%')
                    ->orWhere('nickname', 'like', '%' . $queryParams['q'] . '%');
            })
            ->paginate($perPage);
        return UserListResource::collection($users);
    }

    public function beforeStore($attributes)
    {
        $attributes['profiles'] = json_decode($attributes['profiles']);
        $attributes['external_id'] = Str::uuid()->toString();

        if (isset($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        if (request()->file('avatar_url')) {
            $attributes['avatar_url'] = uploadImage(request()->avatar_url, 'users');
        }

        return $this->create($attributes, true);
    }

    public function beforeUpdate($resource, $attributes)
    {
        $attributes['profiles'] = json_decode($attributes['profiles']);

        if (request()->file('avatar_url')) {
            $attributes['avatar_url'] = uploadImage(request()->avatar_url, 'users');
        }

        return $this->update($resource, $attributes, true);
    }

    public function afterSave($resource, $attributes): Model|JsonResource
    {
        $resource->syncRoles($attributes['profiles']);
        return $resource;
    }

    public function find($id)
    {
        return new UserResource($this->model->with('roles.permissions')->find($id));
    }

    public function changeRole($id, $attributes): Model|JsonResource
    {
        $user = $this->model->find($id);
        $user->syncRoles([$attributes['roles']]);
        $user->active_role = $attributes['roles'];
        $user->save();

        return $user;
    }
}
