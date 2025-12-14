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
     * Nếu không có id trong request, trả về thông tin user hiện tại
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $authenticatedUser = auth()->user();

        // Nếu không có id trong request, lấy thông tin user hiện tại
        if (!$request->has('id')) {
            $user = $authenticatedUser;
            unset($user->password, $user->remember_token);
            return ApiResponse::success($user, 'Lấy thông tin user thành công', 200);
        }

        // Nếu có id, validate và kiểm tra quyền
        $validator = $this->validation->validateGetUser($request);
        if ($valiError = $this->handleValidationErrors($validator)) {
            return $valiError;
        }

        // Kiểm tra quyền: admin có thể xem tất cả, user chỉ xem được chính mình
        if ($authenticatedUser->role !== 'admin' && $authenticatedUser->id != $request->id) {
            return ApiResponse::error('Bạn không có quyền xem thông tin user này', null, 403);
        }

        $user = $this->userService->getUserById($request->id);
        if (!$user) {
            return ApiResponse::error('Lỗi khi lấy thông tin user', null, 500);
        }

        unset($user->password, $user->remember_token);
        return ApiResponse::success($user, 'Lấy thông tin user thành công', 200);
    }
// Cập nhật thông tin user cụ thể với phân quyền
    /**
     * Update user information
     * Nếu không có id trong request, cập nhật user hiện tại
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $authenticatedUser = auth()->user();

        // Nếu không có id trong request, cập nhật user hiện tại
        $userId = $request->input('id', $authenticatedUser->id);

        // Kiểm tra quyền: admin có thể cập nhật tất cả, user chỉ cập nhật được chính mình
        if ($authenticatedUser->role !== 'admin' && $authenticatedUser->id != $userId) {
            return ApiResponse::error('Bạn không có quyền cập nhật thông tin user này', null, 403);
        }

        $validator = $this->validation->validateUpdate($request);
        if ($valiError = $this->handleValidationErrors($validator)) {
            return $valiError;
        }

        $user = $this->userService->updateUser($userId, $request->all());
        return $this->handleServiceResponse(
            $user,
            'Cập nhật user thành công',
            'Không tìm thấy user',
            200,
            404
        );
    }

// Xóa người dùng chỉ Admin
    /**
     * Delete user (Admin only)
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        // Kiểm tra quyền admin
        $authenticatedUser = auth()->user();
        if ($authenticatedUser->role !== 'admin') {
            return ApiResponse::error('Chỉ admin mới có quyền xóa user', null, 403);
        }

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
