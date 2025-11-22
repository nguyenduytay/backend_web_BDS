<?php

namespace App\Services;

use App\Repositories\FavoriteRepository\FavoriteRepositoryInterface;

class FavoriteService extends BaseService
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function getUserFavorites($userId)
    {
        return $this->execute(function () use ($userId) {
            return $this->favoriteRepository->getUserFavorites($userId);
        }, 'FavoriteService::getUserFavorites');
    }

    public function addFavorite($userId, $propertyId)
    {
        return $this->execute(function () use ($userId, $propertyId) {
            return $this->favoriteRepository->addFavorite($userId, $propertyId);
        }, 'FavoriteService::addFavorite');
    }

    public function removeFavorite($userId, $propertyId)
    {
        return $this->execute(function () use ($userId, $propertyId) {
            return $this->favoriteRepository->removeFavorite($userId, $propertyId);
        }, 'FavoriteService::removeFavorite');
    }

    public function checkFavorite($userId, $propertyId)
    {
        return $this->execute(function () use ($userId, $propertyId) {
            return $this->favoriteRepository->isFavorite($userId, $propertyId);
        }, 'FavoriteService::checkFavorite');
    }
}
