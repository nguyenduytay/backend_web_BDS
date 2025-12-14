<?php

namespace App\Http\Controllers\User;

use App\Services\UserService;
use App\Http\Validations\UserValidation;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $userService;
    protected $validation;
    public function __construct(UserService $userService, UserValidation $validation)
    {
        $this->userService = $userService;
        $this->validation = $validation;
    }
    // Lấy danh sách user
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return $this->handleServiceResponseWithEmptyCheck(
            $users,
            'Thành công',
            'Không có người dùng nào',
            200,
            404
        );
    }
    // Tạo user mới
  public function store(Request $request): JsonResponse
{
    $validator = $this->validation->validateCreate($request);
        if ($valiError = $this->handleValidationErrors($validator)) {
            return $valiError;
    }
    $user = $this->userService->createUser($request->all());
        return $this->handleServiceResponse(
            $user,
            'Tạo user thành công',
            'Lỗi khi tạo user',
            201,
            500
        );
}
//Hiển thị thông tin user cụ thể với phân quyền
/**
 * Get specific user information
 * @param Request $request
 * @return JsonResponse
 */
public function show(Request $request): JsonResponse
{
    $validator = $this->validation->validateGetUser($request);
        if ($valiError = $this->handleValidationErrors($validator)) {
            return $valiError;
    }
    
    $authenticatedUser = auth()->user();
    if ($authenticatedUser->role !== 'admin' && $authenticatedUser->id != $request->id) {
        return ApiResponse::error('Bạn không có quyền xem thông tin user này', 403);
    }
    
    $user = $this->userService->getUserById($request->id);
    if (!$user) {
        return ApiResponse::error('Lỗi khi lấy thông tin user', 500);
    }
    
    unset($user->password, $user->remember_token);
    return ApiResponse::success($user, 'Lấy thông tin user thành công', 200);
}
// Cập nhật thông tin user cụ thể với phân quyền
 public function update(Request $request): JsonResponse
    {
        $validator = $this->validation->validateUpdate($request);
        if ($valiError = $this->handleValidationErrors($validator)) {
            return $valiError;
        }
        $user = $this->userService->updateUser($request->id, $request->all());
        return $this->handleServiceResponse(
            $user,
            'Cập nhật user thành công',
            'Không tìm thấy user',
            200,
            404
        );
    }

// Xóa người dùng chỉ Admin
public function destroy(Request $request)
{
    $validated = $request->validate([
        'id' => 'required|integer',
    ]);

    $result = $this->userService->deleteUser($validated['id']);
        return $this->handleServiceResponse(
            $result,
            'Xóa user thành công',
            'Lỗi khi xóa user',
            200,
            500
        );
}
}
