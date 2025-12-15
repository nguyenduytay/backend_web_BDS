# Hướng dẫn tách dữ liệu SQL sang CSV

## 📋 Mô tả

Script JavaScript để tách tất cả các bảng từ file SQL (`test_goline.sql`) thành các file CSV riêng biệt, đặt trong thư mục `csv/`.

## 🚀 Cách sử dụng

### Bước 1: Chạy script

```bash
cd scripts
node extract_all_tables_to_csv.js
```

### Bước 2: Kiểm tra kết quả

Sau khi chạy xong, các file CSV sẽ được tạo trong thư mục `scripts/csv/`:

- `contacts.csv`
- `favorites.csv`
- `features.csv`
- `locations.csv`
- `personal_access_tokens.csv`
- `properties.csv`
- `property_features.csv`
- `property_images.csv` ⭐
- `property_types.csv`
- `users.csv`

## 📊 Cấu trúc file CSV

Mỗi file CSV sẽ có:

- **Dòng đầu tiên**: Tên các cột (header)
- **Các dòng tiếp theo**: Dữ liệu của từng bản ghi
- **Encoding**: UTF-8 với BOM để Excel hiển thị đúng tiếng Việt

### Ví dụ: `property_images.csv`

```csv
id,property_id,image_path,image_name,is_primary,sort_order,created_at,updated_at
1,1,https://res.cloudinary.com/...,marco-grosso-4OyGSc2c0vw-unsplash_ffziqh.jpg,0,1,2025-12-14 03:07:34,2025-12-14 03:07:34
2,1,https://res.cloudinary.com/...,salman-saqib-WaC-JFfF21M-unsplash_yvcull.jpg,0,2,2025-12-14 03:07:34,2025-12-14 03:07:34
...
```

## ✨ Tính năng

- ✅ Tự động tìm tất cả các bảng trong file SQL
- ✅ Parse đúng tên các cột từ câu lệnh INSERT
- ✅ Xử lý đúng các giá trị có dấu phẩy, dấu ngoặc kép
- ✅ Hỗ trợ giá trị NULL
- ✅ Escape đúng format CSV
- ✅ Tự động tạo thư mục `csv/` nếu chưa có
- ✅ Hiển thị tiến trình và thống kê

## 📝 Các bảng được xử lý

Script sẽ tự động tìm và xử lý các bảng sau:

1. `contacts` - Thông tin liên hệ
2. `favorites` - Danh sách yêu thích
3. `features` - Tính năng
4. `locations` - Địa điểm
5. `personal_access_tokens` - Token xác thực
6. `properties` - Bất động sản
7. `property_features` - Tính năng của bất động sản
8. `property_images` - Hình ảnh bất động sản ⭐
9. `property_types` - Loại bất động sản
10. `users` - Người dùng

## ⚠️ Lưu ý

- Script sẽ bỏ qua bảng `migrations` và các view (bảng có `_view` trong tên)
- Nếu một bảng không có dữ liệu, script sẽ bỏ qua và tiếp tục với bảng khác
- File CSV sử dụng UTF-8 encoding với BOM để Excel mở đúng tiếng Việt

## 🔧 Troubleshooting

### Lỗi: "Cannot find module"

```bash
# Đảm bảo đang ở đúng thư mục
cd scripts
node extract_all_tables_to_csv.js
```

### Lỗi: "File không tồn tại"

- Kiểm tra file `test_goline.sql` có trong thư mục `scripts/` không
- Kiểm tra đường dẫn file

### File CSV bị lỗi encoding

- File đã có BOM UTF-8, mở bằng Excel hoặc Notepad++ với encoding UTF-8
- Nếu vẫn lỗi, thử mở bằng Google Sheets

## 📚 So sánh với script PHP

Script JavaScript này tương tự như `extract_property_images_to_csv.php` nhưng:

- ✅ Xử lý **TẤT CẢ** các bảng, không chỉ `property_images`
- ✅ Tự động tìm tất cả các bảng
- ✅ Tạo file CSV cho mỗi bảng
- ✅ Dễ mở rộng và bảo trì

## 🎯 Kết quả mong đợi

Sau khi chạy thành công, bạn sẽ thấy:

```
🚀 Bắt đầu tách dữ liệu từ SQL sang CSV...

📋 Tìm thấy 10 bảng: contacts, favorites, features, ...

📋 Đang xử lý bảng: property_images
   ✅ Tìm thấy INSERT statement tại dòng 2277
   📊 Các cột: id, property_id, image_path, image_name, is_primary, sort_order, created_at, updated_at
   📊 Đã parse được 1464 dòng dữ liệu
   ✅ Đã tạo file CSV: scripts/csv/property_images.csv
   📈 Tổng số dòng: 1465 (bao gồm header)

============================================================
✅ Hoàn tất!
   ✅ Thành công: 10 bảng
   ❌ Thất bại: 0 bảng
📁 Thư mục CSV: scripts/csv
============================================================
```
