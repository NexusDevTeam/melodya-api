<?php

declare(strict_types=1);

namespace App\Repositories\Api\V1\Auth;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Enums\ActiveRoleUser;
use Auth;
use Illuminate\Auth\Events\Registered;
use Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthRepository extends BaseRepository
{
    protected $model = User::class;

    public function register(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        $role = ActiveRoleUser::CLIENT;
        $data['active_role'] = $role;
        $data['external_id'] = Str::uuid()->toString();
        if (isset($data['avatar_url'])) {
            $data['avatar_url'] = uploadImage($data['avatar_url'], 'users/avatar');
        }
        $user = User::create($data);

        $user->assignRole($role);

        event(new Registered($user));

        return $user;
    }

    public function login($request) {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return $this->errorMessage('Unauthorized', 401, ["auth" => "Unauthorized"]);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'user' => new UserResource($user->load('roles.permissions')),
        ]);
    }

    public function findUserForEmail($request) {
        return User::where('email', $request->email)->first();
    }

    public function profile() {
        return new UserResource($this->authUser->load('roles.permissions'));
    }

    public function editProfile($data) {
        if (isset($data['avatar_url'])) {
            $data['avatar_url'] = uploadImage($data['avatar_url'], 'users/avatar');
        }
        $updateSuccessful = $this->authUser->update($data);

        if ($updateSuccessful) {
            return new UserResource($this->authUser->load('roles.permissions'));
        }

        return $this->errorMessage('Profile update failed', 500);
    }

    public function upgradeToArtist(): Model|JsonResource
    {
        $user = $this->model->find($this->authUser->id);
        $user->syncRoles(['artist']);
        $user->active_role = 'artist';
        $user->save();

        return new UserResource($user);
    }

    public function follow($externalId) {
        $user = User::findByExternalId($externalId);

        if (!$user) {
            return $this->errorMessage('User not found', 404);
        }

        if ($this->authUser->isFollowing($user)) {
            return $this->errorMessage('User already followed', 409);
        }

        $this->authUser->following()->attach($user->id);
        return $this->responseMessage('User followed successfully');
    }

    public function unfollow($externalId) {
        $user = User::findByExternalId($externalId);

        if (!$user) {
            return $this->errorMessage('User not found', 404);
        }

        if (!$this->authUser->isFollowing($user)) {
            return $this->errorMessage('User not followed', 409);
        }

        $this->authUser->following()->detach($user->id);
        return $this->responseMessage('User unfollowed successfully');
    }

    public function followingList() {
        return UserResource::collection($this->authUser->following()->get());
    }

    public function followersList() {
        return UserResource::collection($this->authUser->followers()->get());
    }
}
