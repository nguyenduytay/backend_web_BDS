# Scripts lấy ảnh từ Cloudinary

Thư mục này chứa các script để lấy đường dẫn ảnh từ Cloudinary.

## Cài đặt

```bash
npm install cloudinary
```

## Cấu hình

Đảm bảo file `.env` ở thư mục gốc có thông tin Cloudinary:

```env
CLOUDINARY_CLOUD_NAME=your-cloud-name
CLOUDINARY_API_KEY=your-api-key
CLOUDINARY_API_SECRET=your-api-secret
```

## Scripts

### `get-images-from-folders.js` - Lấy ảnh từ nhiều folder ⭐

Lấy ảnh từ một hoặc nhiều folder cùng lúc.

**Cách sử dụng:**

#### Lấy từ folder mặc định (luxury home, background):
```bash
cd scripts
node get-images-from-folders.js
```

#### Lấy từ folder cụ thể:
```bash
cd scripts
node get-images-from-folders.js "luxury home" "background" "other folder"
```

**Kết quả:**
- `all-folders-images.json` - Thông tin đầy đủ về tất cả ảnh từ tất cả folder
- `all-folders-urls.txt` - Tất cả URL (có ghi chú folder)
- `luxury-home-urls.txt` - URL từ folder "luxury home" (nếu có)
- `background-urls.txt` - URL từ folder "background" (nếu có)
- ... (file riêng cho mỗi folder)

## Ví dụ

### Lấy từ 3 folder:
```bash
node get-images-from-folders.js "luxury home" "background" "apartments"
```

Kết quả sẽ có:
- `all-folders-images.json` - Tổng hợp tất cả
- `all-folders-urls.txt` - Tất cả URL
- `luxury-home-urls.txt` - Chỉ URL từ "luxury home"
- `background-urls.txt` - Chỉ URL từ "background"
- `apartments-urls.txt` - Chỉ URL từ "apartments"

## Cấu trúc file JSON

### `all-folders-images.json`:
```json
{
  "total": 30,
  "folder_count": 2,
  "generated_at": "2025-12-14T10:00:00.000Z",
  "folders": [
    {
      "folder": "luxury home",
      "count": 15,
      "images": [
        {
          "url": "https://...",
          "public_id": "...",
          "format": "jpg",
          "dimensions": { "width": 1920, "height": 1080 },
          "size_bytes": 245678,
          "created_at": "2025-12-14T08:00:00Z"
        }
      ]
    }
  ]
}
```

## Lưu ý

- Script tự động tìm file `.env` ở thư mục gốc (parent của `scripts/`)
- Mỗi folder có thể lấy tối đa 1000 ảnh (có thể thay đổi trong code)
- Cloudinary API có rate limit, nếu có nhiều ảnh có thể mất thời gian
- Tên file output sẽ được làm sạch (loại bỏ ký tự đặc biệt) để an toàn

## Xử lý lỗi

Nếu một folder không tìm thấy hoặc có lỗi, script sẽ:
- Bỏ qua folder đó và tiếp tục với các folder khác
- Hiển thị cảnh báo nhưng không dừng toàn bộ quá trình
- Vẫn lưu kết quả từ các folder thành công

