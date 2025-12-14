/**
 * @api {get} /api/property_types/all Get all property types
 * @apiName GetAllPropertyTypes
 * @apiGroup PropertyType
 * @apiVersion 1.0.0
 *
 * @apiSampleRequest /api/property_types/all
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách property types thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/property_types/search_type Search property type by type
 * @apiName SearchPropertyTypeByType
 * @apiGroup PropertyType
 * @apiVersion 1.0.0
 *
 * @apiParam {String} [type] Property type name (query parameter)
 *
 * @apiSampleRequest /api/property_types/search_type?type=Căn hộ
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Tìm kiếm property types thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {post} /api/property_types/create Create property type
 * @apiName CreatePropertyType
 * @apiGroup PropertyType
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {String} type Property type
 * @apiParam {String} name Property type name
 * @apiParam {String} [slug] Slug
 *
 * @apiSampleRequest /api/property_types/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Success
 * {
 *   "status": "success",
 *   "message": "Tạo property type thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {put} /api/property_types/update/:id Update property type
 * @apiName UpdatePropertyType
 * @apiGroup PropertyType
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Property type ID (path parameter)
 * @apiParam {String} type Property type
 * @apiParam {String} [name] Property type name
 *
 * @apiSampleRequest /api/property_types/update/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Cập nhật property type thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {delete} /api/property_types/delete/:id Delete property type
 * @apiName DeletePropertyType
 * @apiGroup PropertyType
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Property type ID (path parameter)
 *
 * @apiSampleRequest /api/property_types/delete/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa property type thành công",
 *   "data": null
 * }
 */
