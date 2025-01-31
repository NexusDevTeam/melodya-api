<?php

namespace App\Http\Requests\Api\V1\Artist;

use App\Http\Requests\CrudRequest;
use App\Models\Artist;

class ArtistFormRequest extends CrudRequest
{
    protected $type = Artist::class;

    /**
     * Rules when editing resource.
     *
     * @return array
     */
    protected function editRules()
    {
        $rules = [
            'artist_name' => ['sometimes', 'required', 'string', 'min:1'],
            'bio' => ['nullable', 'string'],
            'banner_url' => ['nullable', 'string_or_image'],
            'photos_url' => ['nullable', 'array'],
            'photos_url.*' => ['nullable', 'string_or_image'],
            'social_media' => ['nullable', 'array'],
            'social_media.*' => ['nullable', 'string'],
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
            'user_id' => ['nullable', 'string', 'unique:users,id'],
            'artist_name' => ['required', 'string'],
            'bio' => ['required', 'string'],
            'banner_url' => ['nullable', 'string_or_image'],
            'photos_url' => ['nullable', 'array'],
            'photos_url.*' => ['nullable', 'string_or_image'],
            'social_media' => ['nullable', 'array'],
            'social_media.*' => ['nullable', 'string'],
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
