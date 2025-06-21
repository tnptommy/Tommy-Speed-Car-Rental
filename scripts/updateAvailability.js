const fs = require('fs');

// Đường dẫn tới file JSON
const filePath = '../data/cars.json';

// Đọc file JSON
fs.readFile(filePath, 'utf8', (err, data) => {
    if (err) {
        console.error('Error reading file:', err);
        return;
    }

    try {
        // Parse nội dung JSON
        const jsonData = JSON.parse(data);

        // Cập nhật tất cả các xe thành available: true
        jsonData.cars.forEach(car => {
            car.available = true;
        });

        // Ghi lại file JSON
        fs.writeFile(filePath, JSON.stringify(jsonData, null, 4), 'utf8', err => {
            if (err) {
                console.error('Error writing file:', err);
                return;
            }
            console.log('All cars updated to available: true');
        });
    } catch (parseErr) {
        console.error('Error parsing JSON:', parseErr);
    }
});