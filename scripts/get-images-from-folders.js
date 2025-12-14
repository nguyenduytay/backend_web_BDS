/**
 * Script để lấy tất cả đường dẫn ảnh từ nhiều folder trên Cloudinary
 *
 * Cách sử dụng:
 * 1. Cài đặt: npm install cloudinary
 * 2. Cập nhật thông tin Cloudinary từ file .env ở thư mục gốc
 * 3. Chạy: node get-images-from-folders.js
 *
 * Hoặc chỉ định folder cụ thể:
 * node get-images-from-folders.js "luxury home" "background" "other folder"
 */

const cloudinary = require('cloudinary').v2;
const fs = require('fs');
const path = require('path');

// Cấu hình Cloudinary từ file .env
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
 * Lấy tất cả ảnh từ một folder trên Cloudinary
 * @param {string} folderPath - Đường dẫn folder
 * @param {number} maxResults - Số lượng kết quả tối đa (mặc định: 1000)
 * @returns {Promise<Array>} Mảng chứa các object với thông tin ảnh
 */
async function getAllImagesFromFolder(folderPath, maxResults = 1000) {
  try {
    console.log(`\n🔍 Đang tìm kiếm ảnh trong folder: "${folderPath}"...`);

    const allImages = [];
    let nextCursor = null;
    let totalFetched = 0;

    do {
      const result = await cloudinary.search
        .expression(`folder:"${folderPath}"`)
        .max_results(500) // Cloudinary cho phép tối đa 500 mỗi lần
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
        console.log(
          `   ✅ Đã lấy ${images.length} ảnh (Tổng: ${totalFetched})`
        );
      }

      nextCursor = result.next_cursor;

      // Giới hạn số lượng kết quả
      if (totalFetched >= maxResults) {
        console.log(`   ⚠️  Đã đạt giới hạn ${maxResults} ảnh`);
        break;
      }
    } while (nextCursor);

    console.log(
      `   🎉 Hoàn thành folder "${folderPath}": ${allImages.length} ảnh`
    );
    return allImages;
  } catch (error) {
    console.error(
      `   ❌ Lỗi khi lấy ảnh từ folder "${folderPath}":`,
      error.message
    );
    return []; // Trả về mảng rỗng thay vì throw error để tiếp tục với các folder khác
  }
}

/**
 * Lấy ảnh từ nhiều folder
 * @param {Array<string>} folders - Mảng các tên folder
 * @param {number} maxResultsPerFolder - Số lượng kết quả tối đa mỗi folder
 * @returns {Promise<Object>} Object chứa kết quả theo từng folder
 */
async function getAllImagesFromMultipleFolders(
  folders,
  maxResultsPerFolder = 1000
) {
  const results = {};
  let totalImages = 0;

  for (const folder of folders) {
    const images = await getAllImagesFromFolder(folder, maxResultsPerFolder);
    results[folder] = images;
    totalImages += images.length;
  }

  return {
    folders: results,
    total: totalImages,
    folderCount: folders.length,
  };
}

/**
 * Lưu danh sách ảnh từ nhiều folder vào file JSON
 * @param {Object} data - Object chứa kết quả từ nhiều folder
 * @param {string} outputFile - Tên file output
 */
function saveMultipleFoldersToFile(
  data,
  outputFile = 'all-folders-images.json'
) {
  try {
    const outputPath = path.join(__dirname, outputFile);
    const outputData = {
      total: data.total,
      folder_count: data.folderCount,
      generated_at: new Date().toISOString(),
      folders: Object.keys(data.folders).map((folderName) => ({
        folder: folderName,
        count: data.folders[folderName].length,
        images: data.folders[folderName].map((img) => ({
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
      })),
    };

    fs.writeFileSync(outputPath, JSON.stringify(outputData, null, 2), 'utf8');
    console.log(`\n💾 Đã lưu danh sách ảnh vào file: ${outputPath}`);
    console.log(`📊 Tổng số folder: ${data.folderCount}`);
    console.log(`📊 Tổng số ảnh: ${data.total}`);

    return outputPath;
  } catch (error) {
    console.error('❌ Lỗi khi lưu file:', error.message);
    throw error;
  }
}

/**
 * Lưu tất cả URL từ nhiều folder vào file text (mỗi URL một dòng, có ghi chú folder)
 * @param {Object} data - Object chứa kết quả từ nhiều folder
 * @param {string} outputFile - Tên file output
 */
function saveAllUrlsToTextFile(data, outputFile = 'all-folders-urls.txt') {
  try {
    const outputPath = path.join(__dirname, outputFile);
    let content = '';

    Object.keys(data.folders).forEach((folderName) => {
      const images = data.folders[folderName];
      if (images.length > 0) {
        content += `# Folder: ${folderName} (${images.length} ảnh)\n`;
        images.forEach((img) => {
          content += `${img.secure_url}\n`;
        });
        content += '\n';
      }
    });

    fs.writeFileSync(outputPath, content, 'utf8');
    console.log(`💾 Đã lưu danh sách URL vào file: ${outputPath}`);

    return outputPath;
  } catch (error) {
    console.error('❌ Lỗi khi lưu file:', error.message);
    throw error;
  }
}

/**
 * Lưu URL từ từng folder riêng biệt
 * @param {Object} data - Object chứa kết quả từ nhiều folder
 */
function saveUrlsByFolder(data) {
  const savedFiles = [];

  Object.keys(data.folders).forEach((folderName) => {
    const images = data.folders[folderName];
    if (images.length > 0) {
      const safeFolderName = folderName
        .replace(/[^a-z0-9]/gi, '-')
        .toLowerCase();
      const outputFile = `${safeFolderName}-urls.txt`;
      const outputPath = path.join(__dirname, outputFile);
      const urls = images.map((img) => img.secure_url).join('\n');

      fs.writeFileSync(outputPath, urls, 'utf8');
      savedFiles.push(outputFile);
      console.log(
        `   💾 Đã lưu ${images.length} URL từ folder "${folderName}" vào: ${outputFile}`
      );
    }
  });

  return savedFiles;
}

/**
 * Đọc file .env từ thư mục gốc (parent directory)
 */
function loadEnvConfig() {
  // Tìm file .env ở thư mục gốc (parent của scripts/)
  const rootDir = path.resolve(__dirname, '..');
  const envPath = path.join(rootDir, '.env');

  if (fs.existsSync(envPath)) {
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

    console.log('✅ Đã tải cấu hình từ file .env\n');
  } else {
    console.log(
      '⚠️  Không tìm thấy file .env, sử dụng biến môi trường hoặc giá trị mặc định\n'
    );
  }
}

/**
 * Hàm chính
 */
async function main() {
  try {
    // Load config từ .env
    loadEnvConfig();

    // Lấy danh sách folder từ command line arguments hoặc sử dụng mặc định
    const folders =
      process.argv.slice(2).length > 0
        ? process.argv.slice(2)
        : ['luxury home', 'background']; // Mặc định lấy từ 2 folder

    console.log('🚀 Bắt đầu lấy ảnh từ Cloudinary...');
    console.log(`📁 Các folder sẽ lấy: ${folders.join(', ')}\n`);

    // Lấy ảnh từ tất cả folder
    const results = await getAllImagesFromMultipleFolders(folders, 1000);

    if (results.total === 0) {
      console.log('\n⚠️  Không tìm thấy ảnh nào trong các folder đã chỉ định');
      console.log('💡 Kiểm tra lại tên folder hoặc cấu hình Cloudinary');
      return;
    }

    // Lưu vào file JSON tổng hợp
    saveMultipleFoldersToFile(results, 'all-folders-images.json');

    // Lưu tất cả URL vào một file text
    saveAllUrlsToTextFile(results, 'all-folders-urls.txt');

    // Lưu URL từng folder riêng biệt
    console.log('\n📝 Lưu URL từng folder riêng biệt:');
    saveUrlsByFolder(results);

    // Hiển thị thống kê
    console.log('\n📊 Thống kê:');
    Object.keys(results.folders).forEach((folderName) => {
      const count = results.folders[folderName].length;
      console.log(`   📁 ${folderName}: ${count} ảnh`);
    });
    console.log(
      `\n   ✨ Tổng cộng: ${results.total} ảnh từ ${results.folderCount} folder`
    );

    console.log('\n✨ Hoàn tất!');
  } catch (error) {
    console.error('\n❌ Có lỗi xảy ra:', error);
    process.exit(1);
  }
}

// Chạy script nếu được gọi trực tiếp
if (require.main === module) {
  main();
}

// Export để có thể sử dụng như module
module.exports = {
  getAllImagesFromFolder,
  getAllImagesFromMultipleFolders,
  saveMultipleFoldersToFile,
  saveAllUrlsToTextFile,
  saveUrlsByFolder,
};
