/**
 * @api {get} /api/admin/reports/properties_monthly Get properties monthly report
 * @apiName GetPropertiesMonthlyReport
 * @apiGroup Report
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} [year] Year (default: current year)
 * @apiParam {Integer} [month] Month (default: current month)
 *
 * @apiSampleRequest /api/admin/reports/properties_monthly?year=2024&month=12
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy báo cáo properties thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/admin/reports/users_monthly Get users monthly report
 * @apiName GetUsersMonthlyReport
 * @apiGroup Report
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} [year] Year (default: current year)
 * @apiParam {Integer} [month] Month (default: current month)
 *
 * @apiSampleRequest /api/admin/reports/users_monthly?year=2024&month=12
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy báo cáo users thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {get} /api/admin/reports/export_properties Export properties
 * @apiName ExportProperties
 * @apiGroup Report
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {String} [format=csv] Export format (csv, xlsx)
 *
 * @apiSampleRequest /api/admin/reports/export_properties?format=csv
 *
 * @apiSuccessExample {binary} Success-Response:
 * HTTP/1.1 200 Success
 * Content-Type: text/csv
 * Content-Disposition: attachment; filename="properties.csv"
 */

/**
 * @api {get} /api/admin/reports/export_users Export users
 * @apiName ExportUsers
 * @apiGroup Report
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {String} [format=csv] Export format (csv, xlsx)
 *
 * @apiSampleRequest /api/admin/reports/export_users?format=csv
 *
 * @apiSuccessExample {binary} Success-Response:
 * HTTP/1.1 200 Success
 * Content-Type: text/csv
 * Content-Disposition: attachment; filename="users.csv"
 */
