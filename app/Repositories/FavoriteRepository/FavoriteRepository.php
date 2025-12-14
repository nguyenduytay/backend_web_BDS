<?php

namespace App\Repositories\FavoriteRepository;

use App\Models\Favorite;
use App\Repositories\BaseRepository;

class FavoriteRepository extends BaseRepository implements FavoriteRepositoryInterface
{
    public function getModel()
    {
        return Favorite::class;
    }
    public function getUserFavorites($userId)
    {
        return $this->model::with('property')->where('user_id', $userId)->get();
    }

    public function addFavorite($userId, $propertyId)
    {
        $exists = $this->model::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->exists();

        if ($exists) {
            return null;
        }

        return $this->model::firstOrCreate([
            'user_id' => $userId,
            'property_id' => $propertyId,
        ]);
    }

    public function removeFavorite($userId, $propertyId)
    {
        return $this->model::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->delete();
    }

    public function isFavorite($userId, $propertyId)
    {
        return Favorite::query()->where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->exists();
    }
}
