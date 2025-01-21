<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\CrudController;
use App\Models\User;
use App\Repositories\Api\V1\Auth\AuthRepository;

class AuthController extends CrudController
{
    protected $model = User::class;

    public function __construct()
    {
        $this->repository = new AuthRepository();
    }

    public function tokenInformation()
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'You did not provide a valid token.',
            ]);
        }

        return response()->json([
            'message' => 'Your token is valid; you are authorized.',
            'id' => auth()->id(),
            'token' => auth()?->user()?->getAttributes(),
        ]);
    }

    public function userRegister()
    {
        return $this->repository->userRegister();
    }

    public function userSync()
    {
        return $this->repository->userSync();
    }
}
