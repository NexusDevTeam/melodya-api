<?php

namespace App\Http\Requests\Api\V1\User;

use App\Enums\ActiveRoleUser;
use App\Http\Requests\CrudRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Enum;

class UserFormRequest extends CrudRequest
{
    protected $type = User::class;

    /**
     * Rules when editing resource.
     *
     * @return array
     */
    protected function editRules()
    {
        $rules = [
            'name' => ['sometimes','required', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
            'avatar_url' => ['nullable', 'string_or_image'],
            'profile' => ['nullable', 'string', new Enum(ActiveRoleUser::class)],
        ];

        return $rules;
    }

    /**
     * Rules when creating resource.
     *
     * @return array
     */
    protected function createRules()
    {
        $rules = [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'avatar_url' => ['nullable', 'string_or_image'],
            'profile' => ['nullable', 'string', new Enum(ActiveRoleUser::class)],
        ];

        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function baseRules()
    {
        return [];
    }
}
