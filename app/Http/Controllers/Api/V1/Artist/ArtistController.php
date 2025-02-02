<?php

namespace App\Http\Controllers\Api\V1\Artist;

use App\Enums\ActiveRoleUser;
use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Artist\ArtistFormRequest;
use App\Models\Artist;
use App\Repositories\Api\V1\Artist\ArtistRepository;

class ArtistController extends CrudController
{
    protected $model = Artist::class;

    protected $repository = ArtistRepository::class;

    public function store() {
        $params = $this->formParams();

        if (!ActiveRoleUser::admin($this->authUser->active_role)) {
            $params['user_id'] = $this->authUser->id;
        } else {
            $this->validateRequest(
                request(),
                ['user_id' => "required|exists:users,id,active_role," . ActiveRoleUser::ARTIST->value]
            );
        }

        if ($this->model::where('user_id', $params['user_id'])->exists()) {
            return $this->errorMessage('User already has an artist profile', 400);
        }


        try {
            return $this->repository->beforeStore($params);
        } catch (\Exception $ex) {
            return $this->exceptionMessage($ex);
        }
    }

    public function update($id = null)
    {
        $resource = $this->repository->find($id ?? $this->authUser->artist->id);

        if (!$resource) {
            return $this->errorMessage($this->modelName.' not found', $code = 404, $erros = []);
        }

        return $this->repository->update($resource, $this->formParams());
    }

    public function profileArtist()
    {
        return $this->repository->profileArtist();
    }

    public function formRequest(): ArtistFormRequest
    {
        return app(ArtistFormRequest::class);
    }
}
