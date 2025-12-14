/**
 * Script để lấy tất cả đường dẫn ảnh trong thư mục "luxury home" từ Cloudinary
 *
 * Cách sử dụng:
 * 1. Cài đặt: npm install cloudinary axios (hoặc yarn add cloudinary axios)
 * 2. Cập nhật thông tin Cloudinary từ file .env (dòng 59-63)
 * 3. Chạy: node get-luxury-home-images.js
 */

const cloudinary = require('cloudinary').v2;
const fs = require('fs');
const path = require('path');

// Cấu hình Cloudinary từ file .env
// Lấy từ dòng 59-63 trong file .env
const CLOUDINARY_CONFIG = {
  cloud_name: process.env.CLOUDINARY_CLOUD_NAME || 'your-cloud-name',
  api_key: process.env.CLOUDINARY_API_KEY || 'your-api-key',
  api_secret: process.env.CLOUDINARY_API_SECRET || 'your-api-secret',
};

// Khởi tạo Cloudinary
cloudinary.config({
  cloud_name: CLOUDINARY_CONFIG.cloud_name,
  api_key: CLOUDINARY_CONFIG.api_key,
  api_secret: CLOUDINARY_CONFIG.api_secret,
});

/**
 * Lấy tất cả ảnh từ folder "luxury home" trên Cloudinary
 * @param {string} folderPath - Đường dẫn folder (mặc định: "luxury home")
 * @param {number} maxResults - Số lượng kết quả tối đa (mặc định: 500)
 * @returns {Promise<Array>} Mảng chứa các object với thông tin ảnh
 */
async function getAllImagesFromLuxuryHome(
  folderPath = 'luxury home',
  maxResults = 500
) {
  try {
    console.log(`🔍 Đang tìm kiếm ảnh trong folder: "${folderPath}"...`);

    const allImages = [];
    let nextCursor = null;
    let totalFetched = 0;

    do {
      const options = {
        type: 'upload',
        prefix: folderPath,
        max_results: 500, // Cloudinary cho phép tối đa 500 mỗi lần
        resource_type: 'image',
      };

      if (nextCursor) {
        options.next_cursor = nextCursor;
      }

      // Sử dụng Admin API để lấy resources từ folder
      const result = await cloudinary.search
        .expression(`folder:"${folderPath}"`)
        .max_results(options.max_results)
        .execute();

      if (result.resources && result.resources.length > 0) {
        const images = result.resources.map((resource) => ({
          public_id: resource.public_id,
          secure_url: resource.secure_url,
          url: resource.url,
          format: resource.format,
          width: resource.width,
          height: resource.height,
          bytes: resource.bytes,
          created_at: resource.created_at,
          folder: resource.folder || folderPath,
        }));

        allImages.push(...images);
        totalFetched += images.length;
        console.log(`✅ Đã lấy ${images.length} ảnh (Tổng: ${totalFetched})`);
      }

      nextCursor = result.next_cursor;

      // Giới hạn số lượng kết quả
      if (totalFetched >= maxResults) {
        console.log(`⚠️  Đã đạt giới hạn ${maxResults} ảnh`);
        break;
      }
    } while (nextCursor);

    console.log(`\n🎉 Hoàn thành! Tổng cộng: ${allImages.length} ảnh`);
    return allImages;
  } catch (error) {
    console.error('❌ Lỗi khi lấy ảnh từ Cloudinary:', error.message);
    throw error;
  }
}

/**
 * Lưu danh sách đường dẫn ảnh vào file JSON
 * @param {Array} images - Mảng chứa thông tin ảnh
 * @param {string} outputFile - Tên file output (mặc định: 'luxury-home-images.json')
 */
function saveImagesToFile(images, outputFile = 'luxury-home-images.json') {
  try {
    const outputPath = path.join(__dirname, outputFile);
    const data = {
      total: images.length,
      folder: 'luxury home',
      generated_at: new Date().toISOString(),
      images: images.map((img) => ({
        url: img.secure_url,
        public_id: img.public_id,
        format: img.format,
        dimensions: {
          width: img.width,
          height: img.height,
        },
        size_bytes: img.bytes,
        created_at: img.created_at,
      })),
    };

    fs.writeFileSync(outputPath, JSON.stringify(data, null, 2), 'utf8');
    console.log(`\n💾 Đã lưu danh sách ảnh vào file: ${outputPath}`);
    console.log(`📊 Tổng số ảnh: ${data.total}`);

    return outputPath;
  } catch (error) {
    console.error('❌ Lỗi khi lưu file:', error.message);
    throw error;
  }
}

/**
 * Lưu chỉ danh sách URL vào file text (mỗi URL một dòng)
 * @param {Array} images - Mảng chứa thông tin ảnh
 * @param {string} outputFile - Tên file output (mặc định: 'luxury-home-urls.txt')
 */
function saveUrlsToTextFile(images, outputFile = 'luxury-home-urls.txt') {
  try {
    const outputPath = path.join(__dirname, outputFile);
    const urls = images.map((img) => img.secure_url).join('\n');

    fs.writeFileSync(outputPath, urls, 'utf8');
    console.log(`💾 Đã lưu danh sách URL vào file: ${outputPath}`);

    return outputPath;
  } catch (error) {
    console.error('❌ Lỗi khi lưu file:', error.message);
    throw error;
  }
}

/**
 * Hàm chính
 */
async function main() {
  try {
    console.log('🚀 Bắt đầu lấy ảnh từ Cloudinary...\n');
    console.log('📁 Folder: luxury home\n');

    // Lấy tất cả ảnh
    const images = await getAllImagesFromLuxuryHome('luxury home', 1000);

    if (images.length === 0) {
      console.log('⚠️  Không tìm thấy ảnh nào trong folder "luxury home"');
      console.log('💡 Kiểm tra lại tên folder hoặc cấu hình Cloudinary');
      return;
    }

    // Lưu vào file JSON
    saveImagesToFile(images, 'luxury-home-images.json');

    // Lưu chỉ URL vào file text
    saveUrlsToTextFile(images, 'luxury-home-urls.txt');

    // Hiển thị một số ảnh mẫu
    console.log('\n📸 Một số ảnh mẫu:');
    images.slice(0, 5).forEach((img, index) => {
      console.log(`\n${index + 1}. ${img.public_id}`);
      console.log(`   URL: ${img.secure_url}`);
      console.log(`   Kích thước: ${img.width}x${img.height}px`);
      console.log(`   Định dạng: ${img.format}`);
    });

    console.log('\n✨ Hoàn tất!');
  } catch (error) {
    console.error('\n❌ Có lỗi xảy ra:', error);
    process.exit(1);
  }
}

// Chạy script nếu được gọi trực tiếp
if (require.main === module) {
  // Kiểm tra xem có file .env không
  const envPath = path.join(__dirname, '.env');
  if (fs.existsSync(envPath)) {
    // Đọc file .env (đơn giản, không dùng dotenv package)
    const envContent = fs.readFileSync(envPath, 'utf8');
    const envLines = envContent.split('\n');

    envLines.forEach((line) => {
      const match = line.match(/^([^#=]+)=(.*)$/);
      if (match) {
        const key = match[1].trim();
        const value = match[2].trim().replace(/^["']|["']$/g, '');
        if (key.startsWith('CLOUDINARY_')) {
          const envKey = key.replace('CLOUDINARY_', '').toLowerCase();
          if (envKey === 'cloud_name') {
            CLOUDINARY_CONFIG.cloud_name = value;
          } else if (envKey === 'api_key') {
            CLOUDINARY_CONFIG.api_key = value;
          } else if (envKey === 'api_secret') {
            CLOUDINARY_CONFIG.api_secret = value;
          }
        }
      }
    });

    // Cập nhật lại config
    cloudinary.config({
      cloud_name: CLOUDINARY_CONFIG.cloud_name,
      api_key: CLOUDINARY_CONFIG.api_key,
      api_secret: CLOUDINARY_CONFIG.api_secret,
    });
  }

  main();
}

// Export để có thể sử dụng như module
module.exports = {
  getAllImagesFromLuxuryHome,
  saveImagesToFile,
  saveUrlsToTextFile,
};
