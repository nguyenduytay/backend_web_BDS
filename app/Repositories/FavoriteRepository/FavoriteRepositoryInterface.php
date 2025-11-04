<?php

namespace App\Repositories\FavoriteRepository;

use App\Repositories\RepositoryInterface;

interface FavoriteRepositoryInterface extends RepositoryInterface
{
    public function getUserFavorites($userId);

    public function addFavorite($userId, $propertyId);

    public function removeFavorite($userId, $propertyId);

    public function isFavorite($userId, $propertyId);
}
