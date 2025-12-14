/**
 * @api {get} /api/users/:userId/favorites/all Get user favorites
 * @apiName GetUserFavorites
 * @apiGroup Favorite
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} userId User ID (path parameter)
 *
 * @apiSampleRequest /api/users/1/favorites/all
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách favorites thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {post} /api/properties/:propertyId/favorite/create/:userId Create favorite
 * @apiName CreateFavorite
 * @apiGroup Favorite
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} userId User ID (path parameter)
 *
 * @apiSampleRequest /api/properties/1/favorite/create/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Thêm favorite thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {delete} /api/properties/:propertyId/favorite/delete/:userId Delete favorite
 * @apiName DeleteFavorite
 * @apiGroup Favorite
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} userId User ID (path parameter)
 *
 * @apiSampleRequest /api/properties/1/favorite/delete/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa favorite thành công",
 *   "data": null
 * }
 */

/**
 * @api {get} /api/properties/:propertyId/favorite/is_favorite/:userId Check favorite
 * @apiName CheckFavorite
 * @apiGroup Favorite
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} userId User ID (path parameter)
 *
 * @apiSampleRequest /api/properties/1/favorite/is_favorite/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Kiểm tra favorite thành công",
 *   "data": true
 * }
 */
