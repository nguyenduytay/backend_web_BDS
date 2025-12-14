/**
 * @api {get} /api/features/all Get all features
 * @apiName GetAllFeatures
 * @apiGroup Feature
 * @apiVersion 1.0.0
 *
 * @apiSampleRequest /api/features/all
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách features thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/features/search/:id Get feature by ID
 * @apiName GetFeatureById
 * @apiGroup Feature
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} id Feature ID (path parameter)
 *
 * @apiSampleRequest /api/features/search/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thông tin feature thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {post} /api/features/create Create feature
 * @apiName CreateFeature
 * @apiGroup Feature
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {String} name Feature name
 * @apiParam {String} [icon] Icon
 *
 * @apiSampleRequest /api/features/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Success
 * {
 *   "status": "success",
 *   "message": "Tạo feature thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {put} /api/features/update/:id Update feature
 * @apiName UpdateFeature
 * @apiGroup Feature
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Feature ID (path parameter)
 * @apiParam {String} name Feature name
 * @apiParam {String} [icon] Icon
 *
 * @apiSampleRequest /api/features/update/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Cập nhật feature thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {delete} /api/features/delete/:id Delete feature
 * @apiName DeleteFeature
 * @apiGroup Feature
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Feature ID (path parameter)
 *
 * @apiSampleRequest /api/features/delete/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa feature thành công",
 *   "data": null
 * }
 */
