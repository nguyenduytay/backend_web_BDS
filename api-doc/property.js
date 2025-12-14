/**
 * @api {get} /api/properties/all Get all properties
 * @apiName GetAllProperties
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiParam {Number} [page=1] Current page number
 * @apiParam {Number} [per_page=15] Number of records per page
 *
 * @apiSampleRequest /api/properties/all
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách properties thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/properties/by-type/:property_type_id Get properties by type
 * @apiName GetPropertiesByType
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} property_type_id Property type ID (path parameter)
 * @apiParam {Number} [page=1] Current page number
 * @apiParam {Number} [per_page=15] Number of records per page
 *
 * @apiSampleRequest /api/properties/by-type/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách properties thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/properties/by-location Get properties by location
 * @apiName GetPropertiesByLocation
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} [location_id] Location ID (query parameter)
 * @apiParam {Number} [page=1] Current page number
 * @apiParam {Number} [per_page=15] Number of records per page
 *
 * @apiSampleRequest /api/properties/by-location?location_id=1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/properties/featured Get featured properties
 * @apiName GetFeaturedProperties
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiParam {Number} [page=1] Current page number
 * @apiParam {Number} [per_page=15] Number of records per page
 *
 * @apiSampleRequest /api/properties/featured
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/properties/detail/:id Get property detail
 * @apiName GetPropertyDetail
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} id Property ID (path parameter)
 *
 * @apiSampleRequest /api/properties/detail/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thông tin property thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {post} /api/properties/create Create property
 * @apiName CreateProperty
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {String} title Title
 * @apiParam {String} [description] Description
 * @apiParam {Integer} location_id Location ID
 * @apiParam {Integer} property_type_id Property type ID
 * @apiParam {String} status Status (available, sold, rented, pending)
 * @apiParam {Number} price Price
 * @apiParam {Number} area Area
 * @apiParam {Integer} [bedrooms] Number of bedrooms
 * @apiParam {Integer} [bathrooms] Number of bathrooms
 * @apiParam {Integer} [floors] Number of floors
 * @apiParam {String} address Address
 * @apiParam {String} [postal_code] Postal code
 * @apiParam {Number} [latitude] Latitude
 * @apiParam {Number} [longitude] Longitude
 * @apiParam {Integer} [year_built] Year built
 * @apiParam {Integer} contact_id Contact ID
 *
 * @apiSampleRequest /api/properties/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Success
 * {
 *   "status": "success",
 *   "message": "Tạo mới property thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {put} /api/properties/update/:id Update property
 * @apiName UpdateProperty
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin, owner only)
 *
 * @apiParam {Integer} id Property ID (path parameter)
 * @apiParam {String} [title] Title
 * @apiParam {String} [description] Description
 * @apiParam {Integer} [location_id] Location ID
 * @apiParam {Integer} [property_type_id] Property type ID
 * @apiParam {String} [status] Status
 * @apiParam {Number} [price] Price
 * @apiParam {Number} [area] Area
 * @apiParam {Integer} [bedrooms] Number of bedrooms
 * @apiParam {Integer} [bathrooms] Number of bathrooms
 * @apiParam {Integer} [floors] Number of floors
 * @apiParam {String} [address] Address
 * @apiParam {String} [postal_code] Postal code
 * @apiParam {Number} [latitude] Latitude
 * @apiParam {Number} [longitude] Longitude
 * @apiParam {Integer} [year_built] Year built
 * @apiParam {Integer} [contact_id] Contact ID
 *
 * @apiSampleRequest /api/properties/update/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Cập nhật property thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {delete} /api/properties/delete/:id Delete property
 * @apiName DeleteProperty
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin, owner only)
 *
 * @apiParam {Integer} id Property ID (path parameter)
 *
 * @apiSampleRequest /api/properties/delete/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa property thành công",
 *   "data": null
 * }
 */

/**
 * @api {post} /api/properties/restore/:id Restore property
 * @apiName RestoreProperty
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication
 *
 * @apiParam {Integer} id Property ID (path parameter)
 *
 * @apiSampleRequest /api/properties/restore/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Khôi phục property thành công",
 *   "data": null
 * }
 */

/**
 * @api {delete} /api/properties/force/:id Force delete property
 * @apiName ForceDeleteProperty
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication
 *
 * @apiParam {Integer} id Property ID (path parameter)
 *
 * @apiSampleRequest /api/properties/force/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa property thành công",
 *   "data": null
 * }
 */

/**
 * @api {get} /api/properties/user/:userId Get properties by user
 * @apiName GetPropertiesByUser
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication
 *
 * @apiParam {Integer} userId User ID (path parameter)
 *
 * @apiSampleRequest /api/properties/user/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách bất động sản của người dùng thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {post} /api/properties/approve/:id Approve property
 * @apiName ApproveProperty
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Property ID (path parameter)
 *
 * @apiSampleRequest /api/properties/approve/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Duyệt tin đăng thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {post} /api/properties/hide/:id Hide property
 * @apiName HideProperty
 * @apiGroup Property
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Property ID (path parameter)
 *
 * @apiSampleRequest /api/properties/hide/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Ẩn tin đăng thành công",
 *   "data": {...}
 * }
 */
