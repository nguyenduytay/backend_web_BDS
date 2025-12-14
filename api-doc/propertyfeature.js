/**
 * @api {get} /api/properties/:propertyId/features/all Get all property features
 * @apiName GetPropertyFeatures
 * @apiGroup PropertyFeature
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 *
 * @apiSampleRequest /api/properties/1/features/all
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
 * @api {post} /api/properties/:propertyId/features/create Create property feature
 * @apiName CreatePropertyFeature
 * @apiGroup PropertyFeature
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} feature_id Feature ID
 *
 * @apiSampleRequest /api/properties/1/features/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Thêm feature thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {put} /api/properties/:propertyId/features/sync Sync property features
 * @apiName SyncPropertyFeatures
 * @apiGroup PropertyFeature
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Array} feature_ids Array of feature IDs
 *
 * @apiSampleRequest /api/properties/1/features/sync
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Đồng bộ features thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {delete} /api/properties/:propertyId/features/delete/:featureId Delete property feature
 * @apiName DeletePropertyFeature
 * @apiGroup PropertyFeature
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} featureId Feature ID (path parameter)
 *
 * @apiSampleRequest /api/properties/1/features/delete/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa feature thành công",
 *   "data": null
 * }
 */
