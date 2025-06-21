const fs = require('fs');
const path = require('path');

// Đường dẫn tuyệt đối tới file JSON
const filePath = path.join(__dirname, '../../data/cars.json');

// Đọc file JSON
fs.readFile(filePath, 'utf8', (err, data) => {
    if (err) {
        console.error('Error reading file:', err.message);
        return;
    }

    try {
        // Parse nội dung JSON
        const jsonData = JSON.parse(data);

        // Kiểm tra xem có danh sách xe không
        if (!Array.isArray(jsonData.cars)) {
            console.error('Invalid JSON format: "cars" should be an array.');
            return;
        }

        // Cập nhật tất cả các xe thành available: true
        jsonData.cars.forEach(car => {
            car.available = true;
        });

        // Ghi lại file JSON
        fs.writeFile(filePath, JSON.stringify(jsonData, null, 4), 'utf8', err => {
            if (err) {
                console.error('Error writing file:', err.message);
                return;
            }
            console.log('All cars updated to available: true');
        });
    } catch (parseErr) {
        console.error('Error parsing JSON:', parseErr.message);
    }
});