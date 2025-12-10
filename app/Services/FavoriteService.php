<?php

namespace App\Services;

use App\Repositories\FavoriteRepository\FavoriteRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Throwable;

class FavoriteService extends BaseService
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function getUserFavorites($userId)
    {
        try {
            return $this->favoriteRepository->getUserFavorites($userId);
        } catch (Throwable $e) {
            $this->handleException($e, 'FavoriteService::getUserFavorites');
            return null;
        }
    }

    public function addFavorite($userId, $propertyId)
    {
        try {
            return $this->favoriteRepository->addFavorite($userId, $propertyId);
        } catch (Throwable $e) {
            $this->handleException($e, 'FavoriteService::addFavorite');
            return null;
        }
    }

    public function removeFavorite($userId, $propertyId)
    {
        try {
            return $this->favoriteRepository->removeFavorite($userId, $propertyId);
        } catch (Throwable $e) {
            $this->handleException($e, 'FavoriteService::removeFavorite');
            return null;
        }
    }

    public function checkFavorite($userId, $propertyId)
    {
        try {
            return $this->favoriteRepository->isFavorite($userId, $propertyId);
        } catch (Throwable $e) {
            $this->handleException($e, 'FavoriteService::checkFavorite');
            return null;
        }
    }
}
