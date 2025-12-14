/**
 * @api {get} /api/users/index Get user list
 * @apiName GetAllUsers
 * @apiGroup User
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiSampleRequest /api/users/index
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách users thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {post} /api/users/create Create new user
 * @apiName CreateUser
 * @apiGroup User
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {String} name Name
 * @apiParam {String} email Email
 * @apiParam {String} phone Phone number
 * @apiParam {String} password Password
 * @apiParam {String} password_confirmation Password confirmation
 * @apiParam {String} role Role (admin, user, agent)
 *
 * @apiSampleRequest /api/users/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Success
 * {
 *   "status": "success",
 *   "message": "Tạo user thành công",
 *   "data": {
 *     "id": 1,
 *     "name": "New User",
 *     "email": "newuser@test.com",
 *     "role": "user",
 *     "phone": "0912345680"
 *   }
 * }
 */

/**
 * @api {get} /api/users/show Get user profile
 * @apiName GetUserProfile
 * @apiGroup User
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (admin or own profile)
 *
 * @apiParam {Integer} id User ID (query parameter)
 *
 * @apiSampleRequest /api/users/show?id=1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thông tin user thành công",
 *   "data": {
 *     "id": 1,
 *     "name": "Test User",
 *     "email": "test@example.com",
 *     "role": "user",
 *     "phone": "0912345678"
 *   }
 * }
 */

/**
 * @api {put} /api/users/update Update user profile
 * @apiName UpdateUserProfile
 * @apiGroup User
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (admin or own profile)
 *
 * @apiParam {Integer} id User ID (query parameter)
 * @apiParam {String} [name] Name
 * @apiParam {String} [email] Email
 * @apiParam {String} [phone] Phone number
 * @apiParam {String} [password] New password
 * @apiParam {String} [password_confirmation] Password confirmation
 *
 * @apiSampleRequest /api/users/update?id=1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Cập nhật user thành công",
 *   "data": {
 *     "id": 1,
 *     "name": "Updated Name",
 *     "email": "test@example.com",
 *     "role": "user",
 *     "phone": "0912345690"
 *   }
 * }
 */

/**
 * @api {post} /api/users/delete Delete user
 * @apiName DeleteUser
 * @apiGroup User
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id User ID (query parameter)
 *
 * @apiSampleRequest /api/users/delete?id=1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa user thành công",
 *   "data": null
 * }
 */
