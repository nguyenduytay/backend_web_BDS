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
        if ($users->isEmpty()) {
            return ApiResponse::error('Không có người dùng nào', 404);
        }
        return ApiResponse::success($users, 'Thành công', 200);
    }
    // Tạo user mới
  public function store(Request $request): JsonResponse
{
    // Validation
    $validator = $this->validation->validateCreate($request);
    if ($validator->fails()) {
        return ApiResponse::error($validator->errors()->first(), 422);
    }
    // Gọi service với array data
    $user = $this->userService->createUser($request->all());
    return ApiResponse::success($user, 'Tạo user thành công', 201);
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
    if ($validator->fails()) {
        return ApiResponse::error($validator->errors()->first(), 422);
    }
    
    // Lấy user đang đăng nhập từ auth()
    $authenticatedUser = auth()->user();
    // Kiểm tra quyền truy cập
    if ($authenticatedUser->role !== 'admin' && $authenticatedUser->id != $request->id) {
        return ApiResponse::error('Bạn không có quyền xem thông tin user này', 403);
    }
    
    // Lấy thông tin user
    $user = $this->userService->getUserById($request->id);
    if (!$user) {
        return ApiResponse::error('Lỗi khi lấy thông tin user', 500);
    }
    
    // Ẩn thông tin nhạy cảm trước khi trả về
    unset($user->password, $user->remember_token);
    return ApiResponse::success($user, 'Lấy thông tin user thành công', 200);
}
// Cập nhật thông tin user cụ thể với phân quyền
 public function update(Request $request): JsonResponse
    {
        // Validate
        $validator = $this->validation->validateUpdate($request);
        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->first(), 422);
        }
        // Gọi service
        $user = $this->userService->updateUser($request->id, $request->all());
        if (!$user) {
            return ApiResponse::error('Không tìm thấy user', 404);
        }
        return ApiResponse::success($user, 'Cập nhật user thành công');
    }
// Xóa người dùng chỉ Admin
public function destroy(Request $request)
{
    $validated = $request->validate([
        'id' => 'required|integer',
    ]);

    $result = $this->userService->deleteUser($validated['id']);
    if (!$result) {
        return ApiResponse::error('Lỗi khi xóa user', 500);
    }

    return ApiResponse::success(null, 'Xóa user thành công', 200);
}

}
