<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\User\UserFormRequest;
use App\Models\User;
use App\Repositories\Api\V1\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends CrudController
{
    protected $model = User::class;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function changeRole(Request $request, $id)
    {
        $this->validateRequest($request, [
            'roles' => ['required', 'exists:roles,name'],
        ]);

        $this->repository->changeRole($id, $request->all());
        return response()->json(['message' => 'Roles updated successfully']);
    }

    public function formRequest(): UserFormRequest
    {
        return app(UserFormRequest::class);
    }
}
