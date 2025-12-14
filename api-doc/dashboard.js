/**
 * @api {get} /api/admin/dashboard/stats Get dashboard stats
 * @apiName GetDashboardStats
 * @apiGroup Dashboard
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiSampleRequest /api/admin/dashboard/stats
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thống kê thành công",
 *   "data": {
 *     "total_properties": 100,
 *     "total_users": 50,
 *     "total_contacts": 30
 *   }
 * }
 */

/**
 * @api {get} /api/admin/dashboard/property_stats Get property stats
 * @apiName GetPropertyStats
 * @apiGroup Dashboard
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiSampleRequest /api/admin/dashboard/property_stats
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thống kê properties thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/admin/dashboard/user_stats Get user stats
 * @apiName GetUserStats
 * @apiGroup Dashboard
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiSampleRequest /api/admin/dashboard/user_stats
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thống kê users thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/admin/dashboard/recent_properties Get recent properties
 * @apiName GetRecentProperties
 * @apiGroup Dashboard
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} [limit=10] Number of records
 *
 * @apiSampleRequest /api/admin/dashboard/recent_properties?limit=10
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách properties gần đây thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/admin/dashboard/recent_users Get recent users
 * @apiName GetRecentUsers
 * @apiGroup Dashboard
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} [limit=10] Number of records
 *
 * @apiSampleRequest /api/admin/dashboard/recent_users?limit=10
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách users gần đây thành công",
 *   "data": [...]
 * }
 */
