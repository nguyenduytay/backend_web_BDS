<?php

namespace App\Services;

use App\Repositories\FavoriteRepository\FavoriteRepositoryInterface;
use Exception;

class FavoriteService
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
        } catch (Exception $e) {
            return null;
        }
    }

    public function addFavorite($userId, $propertyId)
    {
        try {
            $favorite = $this->favoriteRepository->addFavorite($userId, $propertyId);
            return $favorite;
        } catch (Exception $e) {
            return null;
        }
    }

    public function removeFavorite($userId, $propertyId)
    {
        try {
            $delete = $this->favoriteRepository->removeFavorite($userId, $propertyId);
            return $delete;
        } catch (Exception $e) {
            return null;
        }
    }

    public function checkFavorite($userId, $propertyId)
    {
        try {
            $isFavorite = $this->favoriteRepository->isFavorite($userId, $propertyId);
            return $isFavorite;
        } catch (Exception $e) {
            return null;
        }
    }
}
