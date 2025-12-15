const fs = require('fs');
const path = require('path');

/**
 * Script để tách tất cả các bảng từ file SQL ra CSV
 * Tạo file CSV cho mỗi bảng trong thư mục csv/
 */

const sqlFile = path.join(__dirname, 'test_goline.sql');
const csvDir = path.join(__dirname, 'csv');

// Tạo thư mục csv nếu chưa có
if (!fs.existsSync(csvDir)) {
  fs.mkdirSync(csvDir, { recursive: true });
  console.log(`📁 Đã tạo thư mục: ${csvDir}`);
}

if (!fs.existsSync(sqlFile)) {
  console.error(`❌ Không tìm thấy file: ${sqlFile}`);
  process.exit(1);
}

console.log(`📂 Đang đọc file SQL: ${sqlFile}`);

// Đọc file SQL
const sqlContent = fs.readFileSync(sqlFile, 'utf8');
const lines = sqlContent.split('\n');

/**
 * Parse một dòng VALUES thành mảng giá trị
 */
function parseRow(rowString, columns) {
  rowString = rowString.trim();

  // Xóa dấu ngoặc đơn ở đầu và cuối, và dấu phẩy/chấm phẩy cuối
  rowString = rowString.replace(/^\(|\)[,;]?\s*$/, '');

  const values = [];
  let currentValue = '';
  let inQuotes = false;
  let quoteChar = null;

  for (let i = 0; i < rowString.length; i++) {
    const char = rowString[i];
    const prevChar = i > 0 ? rowString[i - 1] : '';

    if (!inQuotes) {
      if (char === "'" || char === '"') {
        inQuotes = true;
        quoteChar = char;
        currentValue += char;
      } else if (char === ',' && currentValue.trim() !== '') {
        // Dấu phẩy phân cách giá trị
        values.push(cleanValue(currentValue.trim()));
        currentValue = '';
      } else {
        currentValue += char;
      }
    } else {
      currentValue += char;
      // Kết thúc quote nếu gặp quote không được escape
      if (char === quoteChar && prevChar !== '\\') {
        inQuotes = false;
        quoteChar = null;
      }
    }
  }

  // Thêm giá trị cuối cùng
  if (currentValue.trim()) {
    values.push(cleanValue(currentValue.trim()));
  }

  // Đảm bảo số lượng giá trị khớp với số cột
  while (values.length < columns.length) {
    values.push('');
  }

  // Loại bỏ các giá trị thừa (nếu có)
  return values.slice(0, columns.length);
}

/**
 * Làm sạch giá trị: xóa dấu ngoặc kép, unescape
 */
function cleanValue(value) {
  if (!value) return '';

  value = String(value).trim();

  // Xóa dấu ngoặc đơn thừa ở cuối trước (nếu có)
  value = value.replace(/\)+$/, '');

  // Xóa dấu ngoặc kép hoặc đơn ở đầu và cuối
  if (
    (value[0] === "'" && value[value.length - 1] === "'") ||
    (value[0] === '"' && value[value.length - 1] === '"')
  ) {
    value = value.slice(1, -1);
    // Unescape
    value = value.replace(/\\'/g, "'");
    value = value.replace(/\\"/g, '"');
    value = value.replace(/\\\\/g, '\\');
  }

  // Xử lý NULL
  if (value.toUpperCase().trim() === 'NULL') {
    return '';
  }

  return value;
}

/**
 * Escape giá trị cho CSV (xử lý dấu phẩy, dấu ngoặc kép, xuống dòng)
 */
function escapeCSV(value) {
  if (value === null || value === undefined) {
    return '';
  }

  const stringValue = String(value);

  // Nếu có dấu phẩy, dấu ngoặc kép, hoặc xuống dòng, cần đặt trong dấu ngoặc kép
  if (
    stringValue.includes(',') ||
    stringValue.includes('"') ||
    stringValue.includes('\n')
  ) {
    // Escape dấu ngoặc kép bằng cách nhân đôi
    return '"' + stringValue.replace(/"/g, '""') + '"';
  }

  return stringValue;
}

/**
 * Tìm và parse dữ liệu của một bảng
 */
function extractTableData(tableName) {
  console.log(`\n📋 Đang xử lý bảng: ${tableName}`);

  // Tìm dòng INSERT INTO
  const insertPattern = new RegExp(
    `INSERT INTO\\s+\`${tableName}\`\\s+\\(([^)]+)\\)`,
    'i'
  );
  let insertLineIndex = -1;
  let columns = [];

  for (let i = 0; i < lines.length; i++) {
    const match = lines[i].match(insertPattern);
    if (match) {
      insertLineIndex = i;
      // Lấy tất cả các cột
      const columnsMatch = match[1].match(/`([^`]+)`/g);
      if (columnsMatch) {
        columns = columnsMatch.map((col) => col.replace(/`/g, ''));
      }
      console.log(`   ✅ Tìm thấy INSERT statement tại dòng ${i + 1}`);
      console.log(`   📊 Các cột: ${columns.join(', ')}`);
      break;
    }
  }

  if (insertLineIndex === -1) {
    console.log(`   ⚠️  Không tìm thấy INSERT statement cho bảng ${tableName}`);
    return null;
  }

  // Parse các dòng VALUES
  const rows = [];
  let currentRow = '';

  for (let i = insertLineIndex + 1; i < lines.length; i++) {
    let line = lines[i].trim();

    // Bỏ qua dòng trống
    if (!line) {
      continue;
    }

    // Nếu gặp dòng INSERT khác hoặc CREATE TABLE khác, dừng lại
    if (/^(INSERT INTO|CREATE TABLE|--)/i.test(line)) {
      break;
    }

    // Nếu dòng bắt đầu bằng dấu ngoặc đơn, đây là một row mới
    if (/^\(/.test(line)) {
      // Xử lý row trước đó nếu có
      if (currentRow) {
        try {
          const parsedRow = parseRow(currentRow, columns);
          if (parsedRow) {
            rows.push(parsedRow);
          }
        } catch (e) {
          console.log(`   ⚠️  Lỗi khi parse row: ${e.message}`);
        }
      }
      currentRow = line;
    } else {
      // Tiếp tục row hiện tại
      currentRow += ' ' + line;
    }

    // Nếu dòng kết thúc bằng dấu ngoặc đơn và dấu phẩy hoặc dấu chấm phẩy, đây là row cuối
    if (/\)[,;]\s*$/.test(line)) {
      try {
        const parsedRow = parseRow(currentRow, columns);
        if (parsedRow) {
          rows.push(parsedRow);
        }
      } catch (e) {
        console.log(`   ⚠️  Lỗi khi parse row: ${e.message}`);
      }
      currentRow = '';
    }
  }

  // Xử lý row cuối cùng nếu còn
  if (currentRow) {
    try {
      const parsedRow = parseRow(currentRow, columns);
      if (parsedRow) {
        rows.push(parsedRow);
      }
    } catch (e) {
      console.log(`   ⚠️  Lỗi khi parse row: ${e.message}`);
    }
  }

  console.log(`   📊 Đã parse được ${rows.length} dòng dữ liệu`);

  return {
    columns,
    rows,
  };
}

/**
 * Tạo file CSV từ dữ liệu
 */
function createCSV(tableName, columns, rows) {
  const csvFile = path.join(csvDir, `${tableName}.csv`);

  // Tạo nội dung CSV
  let csvContent = '';

  // BOM UTF-8 để Excel hiển thị đúng tiếng Việt
  csvContent += '\uFEFF';

  // Header
  csvContent += columns.map(escapeCSV).join(',') + '\n';

  // Dữ liệu
  for (const row of rows) {
    csvContent += row.map(escapeCSV).join(',') + '\n';
  }

  // Ghi file
  fs.writeFileSync(csvFile, csvContent, 'utf8');

  console.log(`   ✅ Đã tạo file CSV: ${csvFile}`);
  console.log(`   📈 Tổng số dòng: ${rows.length + 1} (bao gồm header)`);

  return csvFile;
}

/**
 * Tìm tất cả các bảng trong file SQL
 */
function findAllTables() {
  const tables = [];
  const tablePattern = /CREATE TABLE (?:IF NOT EXISTS )?`([^`]+)`/gi;

  for (const line of lines) {
    const match = tablePattern.exec(line);
    if (match && match[1]) {
      const tableName = match[1];
      // Bỏ qua view và các bảng hệ thống
      if (
        tableName &&
        !tableName.includes('_view') &&
        tableName !== 'migrations'
      ) {
        if (!tables.includes(tableName)) {
          tables.push(tableName);
        }
      }
    }
  }

  // Reset regex
  tablePattern.lastIndex = 0;

  return tables;
}

// Main execution
console.log('\n🚀 Bắt đầu tách dữ liệu từ SQL sang CSV...\n');

// Tìm tất cả các bảng
const allTables = findAllTables();
console.log(`📋 Tìm thấy ${allTables.length} bảng: ${allTables.join(', ')}\n`);

// Xử lý từng bảng
let successCount = 0;
let failCount = 0;

for (const tableName of allTables) {
  try {
    const tableData = extractTableData(tableName);

    if (tableData && tableData.rows.length > 0) {
      createCSV(tableName, tableData.columns, tableData.rows);
      successCount++;
    } else {
      console.log(
        `   ⚠️  Bảng ${tableName} không có dữ liệu hoặc không tìm thấy`
      );
      failCount++;
    }
  } catch (error) {
    console.error(`   ❌ Lỗi khi xử lý bảng ${tableName}: ${error.message}`);
    failCount++;
  }
}

console.log('\n' + '='.repeat(60));
console.log(`✅ Hoàn tất!`);
console.log(`   ✅ Thành công: ${successCount} bảng`);
console.log(`   ❌ Thất bại: ${failCount} bảng`);
console.log(`📁 Thư mục CSV: ${csvDir}`);
console.log('='.repeat(60));
