<?php

namespace App\Http\Controllers\Api\V1\Artist;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\Artist\ArtistFormRequest;
use App\Models\Artist;
use App\Repositories\Api\V1\Artist\ArtistRepository;

class ArtistController extends CrudController
{
    protected $model = Artist::class;

    public function __construct()
    {
        $this->repository = new ArtistRepository();
    }

    public function formRequest(): ArtistFormRequest
    {
        return app(ArtistFormRequest::class);
    }
}
