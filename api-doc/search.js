/**
 * @api {get} /api/search/properties Search properties
 * @apiName SearchProperties
 * @apiGroup Search
 * @apiVersion 1.0.0
 *
 * @apiParam {String} [q] Search query
 * @apiParam {Number} [page=1] Current page number
 * @apiParam {Number} [per_page=15] Number of records per page
 *
 * @apiSampleRequest /api/search/properties?q=apartment
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Tìm kiếm properties thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/search/filter Filter properties
 * @apiName FilterProperties
 * @apiGroup Search
 * @apiVersion 1.0.0
 *
 * @apiParam {Integer} [property_type_id] Property type ID
 * @apiParam {Integer} [location_id] Location ID
 * @apiParam {Number} [min_price] Minimum price
 * @apiParam {Number} [max_price] Maximum price
 * @apiParam {Integer} [bedrooms] Number of bedrooms
 * @apiParam {Integer} [bathrooms] Number of bathrooms
 * @apiParam {Number} [page=1] Current page number
 * @apiParam {Number} [per_page=15] Number of records per page
 *
 * @apiSampleRequest /api/search/filter?property_type_id=1&min_price=1000000&max_price=5000000
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lọc properties thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/search/autocomplete Autocomplete search
 * @apiName AutocompleteSearch
 * @apiGroup Search
 * @apiVersion 1.0.0
 *
 * @apiParam {String} q Search query (required)
 *
 * @apiSampleRequest /api/search/autocomplete?q=apart
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Tìm kiếm gợi ý thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/search/nearby Nearby search
 * @apiName NearbySearch
 * @apiGroup Search
 * @apiVersion 1.0.0
 *
 * @apiParam {Number} lat Latitude (required)
 * @apiParam {Number} lng Longitude (required)
 * @apiParam {Number} [radius=5] Search radius in km
 *
 * @apiSampleRequest /api/search/nearby?lat=21.0285&lng=105.8542&radius=10
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Tìm kiếm nearby thành công",
 *   "data": [...]
 * }
 */
