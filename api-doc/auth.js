/**
 * @api {post} /api/auth/register User Register
 * @apiName RegisterUser
 * @apiGroup Auth
 * @apiVersion 1.0.0
 *
 * @apiParam {String} name User name
 * @apiParam {String} email User email
 * @apiParam {String} password User password (min:8, must contain uppercase, lowercase, number and special character)
 * @apiParam {String} password_confirmation Password confirmation
 * @apiParam {String} phone User phone
 * @apiParam {String} role User role (admin, user, agent)
 *
 * @apiSampleRequest /api/auth/register
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Success
 * {
 *   "status": "success",
 *   "message": "Đăng ký thành công",
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
 * @api {post} /api/auth/login User Login
 * @apiName LoginUser
 * @apiGroup Auth
 * @apiVersion 1.0.0
 *
 * @apiParam {String} email User email
 * @apiParam {String} password User password
 *
 * @apiSampleRequest /api/auth/login
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Đăng nhập thành công",
 *   "data": "1|token_string_here"
 * }
 */

/**
 * @api {post} /api/auth/logout User Logout
 * @apiName LogoutUser
 * @apiGroup Auth
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication
 *
 * @apiSampleRequest /api/auth/logout
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Đăng xuất thành công.",
 *   "data": null
 * }
 */

/**
 * @api {post} /api/auth/refresh Refresh Token
 * @apiName RefreshToken
 * @apiGroup Auth
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication
 *
 * @apiSampleRequest /api/auth/refresh
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Làm mới token thành công",
 *   "data": {
 *     "token": "new_token_string"
 *   }
 * }
 */

/**
 * @api {get} /api/auth/me Get user profile
 * @apiName GetUserProfile
 * @apiGroup Auth
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication
 *
 * @apiSampleRequest /api/auth/me
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
 * @api {post} /api/auth/forgot_password Forgot Password
 * @apiName ForgotPassword
 * @apiGroup Auth
 * @apiVersion 1.0.0
 *
 * @apiParam {String} email User email
 *
 * @apiSampleRequest /api/auth/forgot_password
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Đường link đặt lại mật khẩu đã được gửi đến email của bạn.",
 *   "data": null
 * }
 */

/**
 * @api {post} /api/auth/reset_password Reset Password
 * @apiName ResetPassword
 * @apiGroup Auth
 * @apiVersion 1.0.0
 *
 * @apiParam {String} token Reset password token
 * @apiParam {String} email User email
 * @apiParam {String} password New password
 * @apiParam {String} password_confirmation Password confirmation
 *
 * @apiSampleRequest /api/auth/reset_password
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Đặt lại mật khẩu thành công",
 *   "data": null
 * }
 */
