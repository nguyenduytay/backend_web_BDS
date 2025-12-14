/**
 * @api {get} /api/locations/all Get all locations
 * @apiName GetAllLocations
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiSampleRequest /api/locations/all
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách locations thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/locations/cities Get cities
 * @apiName GetCities
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiSampleRequest /api/locations/cities
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách cities thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/locations/cities/:city/districts Get districts by city
 * @apiName GetDistrictsByCity
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiParam {String} city City name (path parameter)
 *
 * @apiSampleRequest /api/locations/cities/Hà Nội/districts
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách districts thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/locations/search_city Search location by city
 * @apiName SearchLocationByCity
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiParam {String} [city] City name (query parameter)
 *
 * @apiSampleRequest /api/locations/search_city?city=Hà Nội
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Tìm kiếm locations thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/locations/search/:id Get location by ID
 * @apiName GetLocationById
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} id Location ID (path parameter)
 *
 * @apiSampleRequest /api/locations/search/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thông tin location thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {post} /api/locations/create Create location
 * @apiName CreateLocation
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {String} city City name
 * @apiParam {String} district District name
 *
 * @apiSampleRequest /api/locations/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Success
 * {
 *   "status": "success",
 *   "message": "Tạo location thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {put} /api/locations/update Update location
 * @apiName UpdateLocation
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Location ID
 * @apiParam {String} [city] City name
 * @apiParam {String} [district] District name
 *
 * @apiSampleRequest /api/locations/update
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Cập nhật location thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {delete} /api/locations/delete Delete location
 * @apiName DeleteLocation
 * @apiGroup Location
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Location ID
 *
 * @apiSampleRequest /api/locations/delete
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa location thành công",
 *   "data": null
 * }
 */
