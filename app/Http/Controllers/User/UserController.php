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
 * @param int $id
 * @return JsonResponse
 */
public function show(Request $request): JsonResponse
{
    try {
       $validator = $this->validation->validateGetUser($request);
    if ($validator->fails()) {
        return ApiResponse::error($validator->errors()->first(), 422);
    }
    // Nếu pass validation thì lấy user từ service
    $user = $this->userService->getUserById($request->id);
    return ApiResponse::success($user, 'Lấy user thành công');
        // Lấy user đang đăng nhập từ auth()
        $authenticatedUser = auth()->user();
        // Kiểm tra quyền truy cập
        if ($authenticatedUser->role !== 'admin' && $authenticatedUser->id != $request->id) {
            return ApiResponse::error('Bạn không có quyền xem thông tin user này', 403);
        }
        // Lấy thông tin user
        $user = $this->userService->getUserById($request->id);
        if (!$user) {
            return ApiResponse::error('Không tìm thấy user', 404);
        }
        // Ẩn thông tin nhạy cảm trước khi trả về
        unset($user->password, $user->remember_token);
        return ApiResponse::success($user, 'Lấy thông tin user thành công', 200);
    } catch (\Exception $e) {
        return ApiResponse::error('Lỗi server: ' . $e->getMessage(), 500);
    }
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

    try {
        $result = $this->userService->deleteUser($validated['id']);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'User không tồn tại hoặc không thể xóa.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json([
            'success' => true,
            'message' => 'Xóa user thành công.'
        ], 200, [], JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi hệ thống: ' . $e->getMessage()
        ], 500, [], JSON_UNESCAPED_UNICODE);
    }
}

}
