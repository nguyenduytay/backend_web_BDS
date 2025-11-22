<?php

namespace App\Http\Controllers\Favorite;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\FavoriteValidation;
use App\Services\FavoriteService;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    protected $favoriteService;
    protected $favoriteValidation;

    public function __construct(FavoriteService $favoriteService, FavoriteValidation $favoriteValidation)
    {
        $this->favoriteValidation = $favoriteValidation;
        $this->favoriteService = $favoriteService;
    }

    // GET /api/users/{userId}/favorites
    public function all($userId)
    {
        $valiUserId = $this->favoriteValidation->checkIdValidation($userId, 'users', 'id');
        if ($valiUserId->fails()) {
            return ApiResponse::error($userId->errors()->first());
        }

        $data = $this->favoriteService->getUserFavorites($userId);
        if ($data !== null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error('Không tìm thấy danh sách yêu thích');
    }

    // POST /api/properties/{propertyId}/favorite
    public function create($propertyId, $userId)
    {
        $valiUserId = $this->favoriteValidation->checkIdValidation(
            $userId,
            'users',
            'id'
        );
        if ($valiUserId->fails()) {
            return ApiResponse::error($userId->errors()->first());
        }
        $valiPropertyId = $this->favoriteValidation->checkIdValidation(
            $propertyId,
            'properties',
            'id'
        );
        if ($valiPropertyId->fails()) {
            return ApiResponse::error($propertyId->errors()->first());
        }
        $status = $this->favoriteService->addFavorite($userId, $propertyId);
        if ($status !== null) {
            return ApiResponse::success($status);
        }
        return ApiResponse::error('Lỗi khi thêm yêu thích', 500);
    }

    // DELETE /api/properties/{propertyId}/favorite
    public function delete($propertyId, $userId)
    {
        $valiPropertyId = $this->favoriteValidation->checkIdValidation(
            $propertyId,
            'properties',
            'id'
        );
        if ($valiPropertyId->fails()) {
            return ApiResponse::error($propertyId->errors()->first());
        }
        $valiUserId = $this->favoriteValidation->checkIdValidation(
            $userId,
            'users',
            'id'
        );
        if ($valiUserId->fails()) {
            return ApiResponse::error($userId->errors()->first());
        }
        $status = $this->favoriteService->removeFavorite($userId, $propertyId);
        if ($status !== null) {
            return ApiResponse::success($status);
        }
        return ApiResponse::error('Lỗi khi xóa yêu thích', 500);
    }

    // GET /api/properties/{propertyId}/is-favorite
    public function check($propertyId, $userId)
    {
        $valiPropertyId = $this->favoriteValidation->checkIdValidation(
            $propertyId,
            'properties',
            'id'
        );
        if ($valiPropertyId->fails()) {
            return ApiResponse::error($propertyId->errors()->first());
        }
        $valiUserId = $this->favoriteValidation->checkIdValidation(
            $userId,
            'users',
            'id'
        );
        if ($valiUserId->fails()) {
            return ApiResponse::error($userId->errors()->first());
        }
        $status = $this->favoriteService->checkFavorite($userId, $propertyId);
        if ($status != null) {
            return ApiResponse::success($status, 'Tìm thấy yêu thích');
        }
        return ApiResponse::error('Lỗi khi kiểm tra yêu thích', 500);
    }
}
