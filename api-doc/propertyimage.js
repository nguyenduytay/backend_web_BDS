/**
 * @api {get} /api/property_image/:propertyId/all Get all property images
 * @apiName GetPropertyImages
 * @apiGroup PropertyImage
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 *
 * @apiSampleRequest /api/property_image/1/all
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách images thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {post} /api/property_image/:propertyId/create Create property image
 * @apiName CreatePropertyImage
 * @apiGroup PropertyImage
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {File} image_path Image file (required, image, mimes:jpeg,png,jpg,gif, max:2048KB)
 * @apiParam {String} [image_name] Image name
 * @apiParam {Boolean} [is_primary] Is primary image
 *
 * @apiSampleRequest /api/property_image/1/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Tạo image thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/property_image/:propertyId/show/:imageId Get property image
 * @apiName GetPropertyImage
 * @apiGroup PropertyImage
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} imageId Image ID (path parameter)
 *
 * @apiSampleRequest /api/property_image/1/show/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thông tin image thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {post} /api/property_image/:propertyId/update/:imageId Update property image
 * @apiName UpdatePropertyImage
 * @apiGroup PropertyImage
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} imageId Image ID (path parameter)
 * @apiParam {File} [image_path] Image file
 * @apiParam {String} [image_name] Image name
 * @apiParam {Boolean} [is_primary] Is primary image
 *
 * @apiSampleRequest /api/property_image/1/update/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Cập nhật image thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {delete} /api/property_image/:propertyId/delete/:imageId Delete property image
 * @apiName DeletePropertyImage
 * @apiGroup PropertyImage
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Integer} imageId Image ID (path parameter)
 *
 * @apiSampleRequest /api/property_image/1/delete/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa image thành công",
 *   "data": null
 * }
 */

/**
 * @api {post} /api/property_image/:propertyId/delete_multiple Delete multiple property images
 * @apiName DeleteMultiplePropertyImages
 * @apiGroup PropertyImage
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 * @apiParam {Array} image_ids Array of image IDs
 *
 * @apiSampleRequest /api/property_image/1/delete_multiple
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa images thành công",
 *   "data": null
 * }
 */

/**
 * @api {get} /api/property_image/home_avatars Get home avatars
 * @apiName GetHomeAvatars
 * @apiGroup PropertyImage
 * @apiVersion 1.0.0
 *
 * @apiSampleRequest /api/property_image/home_avatars
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách avatars thành công",
 *   "data": [...]
 * }
 */
