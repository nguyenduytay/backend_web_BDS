/**
 * @api {get} /api/contacts/all Get all contacts
 * @apiName GetAllContacts
 * @apiGroup Contact
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiSampleRequest /api/contacts/all
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy danh sách contacts thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {get} /api/contacts/search Search contacts
 * @apiName SearchContacts
 * @apiGroup Contact
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} [id] Contact ID (query parameter)
 *
 * @apiSampleRequest /api/contacts/search?id=1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Tìm kiếm contacts thành công",
 *   "data": [...]
 * }
 */

/**
 * @api {post} /api/contacts/create Create contact
 * @apiName CreateContact
 * @apiGroup Contact
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {String} name Name
 * @apiParam {String} phone Phone number
 * @apiParam {String} [email] Email
 * @apiParam {Integer} [user_id] User ID
 *
 * @apiSampleRequest /api/contacts/create
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 201 Success
 * {
 *   "status": "success",
 *   "message": "Tạo contact thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {put} /api/contacts/update/:id Update contact
 * @apiName UpdateContact
 * @apiGroup Contact
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Contact ID (path parameter)
 * @apiParam {String} [name] Name
 * @apiParam {String} [phone] Phone number
 * @apiParam {String} [email] Email
 * @apiParam {Integer} [user_id] User ID
 *
 * @apiSampleRequest /api/contacts/update/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Cập nhật contact thành công",
 *   "data": {...}
 * }
 */

/**
 * @api {delete} /api/contacts/delete/:id Delete contact
 * @apiName DeleteContact
 * @apiGroup Contact
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication and admin role
 *
 * @apiParam {Integer} id Contact ID (path parameter)
 *
 * @apiSampleRequest /api/contacts/delete/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Xóa contact thành công",
 *   "data": null
 * }
 */

/**
 * @api {get} /api/contacts/seller/:propertyId Get seller contact
 * @apiName GetSellerContact
 * @apiGroup Contact
 * @apiVersion 1.0.0
 *
 * @apiHeader {String} Authorization Bearer token
 *
 * @apiDescription Protected endpoint - Requires authentication (user or admin)
 *
 * @apiParam {Integer} propertyId Property ID (path parameter)
 *
 * @apiSampleRequest /api/contacts/seller/1
 *
 * @apiSuccessExample {json} Success-Response:
 * HTTP/1.1 200 Success
 * {
 *   "status": "success",
 *   "message": "Lấy thông tin liên hệ người bán thành công",
 *   "data": {
 *     "name": "Nguyễn Văn A",
 *     "phone": "0912345678",
 *     "email": "seller@example.com"
 *   }
 * }
 */
