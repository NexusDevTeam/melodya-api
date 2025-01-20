<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Api\V1\Auth\AuthRepository;

class AuthController extends Controller
{
    protected $model = User::class;

    public function __construct()
    {
        $this->repository = new AuthRepository();
    }
}
