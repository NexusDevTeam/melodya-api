<?php

namespace App\Repositories\Api\V1\Artist;

use App\Enums\ActiveRoleUser;
use App\Models\Artist;
use App\Repositories\BaseRepository;
use Str;

class ArtistRepository extends BaseRepository
{
    protected $model = Artist::class;

    public function all($queryParams)
    {
        $perPage = $queryParams['per_page'] ?? 25;
        $artists = $this->model
            ->when(isset($queryParams['q']), function ($query) use ($queryParams) {
                $query->where('artist_name', 'like', '%' . $queryParams['q'] . '%');
            })
            ->paginate($perPage);
        return $artists;
    }

    public function beforeStore($attributes)
    {
        $attributes['external_id'] = Str::uuid()->toString();

        if (request()->file('banner_url')) {
            $attributes['banner_url'] = uploadImage(request()->banner_url, 'artists/banner');
        }

        if (isset($attributes['photos_url'])) {
            $photos = [];
            foreach (request()->file('photos_url') as $photo) {
                $photos[] = uploadImage($photo, 'artists/banner');
            }
            $attributes['photos_url'] = $photos;
        }

        return $this->create($attributes, true);
    }

    public function beforeUpdate($resource, $attributes)
    {
        if (request()->file('banner_url')) {
            if ($resource->banner_url) {
                deleteImage($resource->banner_url);
            }
            $attributes['banner_url'] = uploadImage(request()->banner_url, 'artists/banner');
        }

        if (isset($attributes['photos_url'])) {
            $oldPhotos = json_decode($resource->photos_url, true);

            $newPhotos = [];
            foreach (request()->file('photos_url') as $photo) {
                $newPhotos[] = uploadImage($photo, 'artists/banner');
            }

            $photosToDelete = array_diff($oldPhotos, $newPhotos);
            foreach ($photosToDelete as $photo) {
                deleteImage($photo);
            }

            $photosToKeep = array_diff($oldPhotos, $photosToDelete);
            $allPhotos = array_merge($photosToKeep, $newPhotos);

            $attributes['photos_url'] = json_encode($allPhotos);
        }

        return $this->update($resource, $attributes, true);
    }
}
