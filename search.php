<?php
include 'includes/functions.php';

header('Content-Type: text/html');

$keyword = strtolower(trim($_GET['keyword'] ?? ''));
$type = strtolower(trim($_GET['type'] ?? ''));
$brand = strtolower(trim($_GET['brand'] ?? ''));
$cars = getAllCars(); // Lấy toàn bộ danh sách xe gốc

$filtered = array_filter($cars, function($car) use ($keyword, $type, $brand) {
    $model = strtolower(trim($car['carModel'] ?? ''));
    $carBrand = strtolower(trim($car['brand'] ?? ''));
    $carType = strtolower(trim($car['carType'] ?? ''));
    $desc = strtolower(trim($car['description'] ?? ''));
    $brandModel = trim($carBrand . ' ' . $model);

    $matchKeyword = $keyword === '' || strpos($model, $keyword) !== false ||
        strpos($carBrand, $keyword) !== false ||
        strpos($carType, $keyword) !== false ||
        strpos($desc, $keyword) !== false ||
        strpos($brandModel, $keyword) !== false;

    $matchType = $type === '' || $carType === $type;
    $matchBrand = $brand === '' || $carBrand === $brand;

    return $matchKeyword && $matchType && $matchBrand;
});

echo "<div class='car-grid'>";
foreach ($filtered as $car) {
    $brand = $car['brand'] ?? 'Unknown Brand';
    $model = $car['carModel'] ?? 'Unknown Model';
    $carType = $car['carType'] ?? 'Unknown Type';
    $fuelType = $car['fuelType'] ?? 'Unknown Fuel';
    $seats = $car['seats'] ?? 'Unknown Seats';
    $mileage = $car['mileage'] ?? 'Unknown Mileage';
    $price = isset($car['pricePerDay']) ? '$' . $car['pricePerDay'] : 'N/A';
    $availability = $car['availability'] ?? 0;
    $image = !empty($car['image']) ? $car['image'] : 'assets/images/no-image.png';

    echo "<div class='car-card'>";
    if (!empty($image)) {
        echo "<img src='{$image}' alt='" . htmlspecialchars($model) . "'>";
    }
    echo "<h3>" . htmlspecialchars($model) . "</h3>";
    echo "<p>" . htmlspecialchars($car['description']) . "</p>";
    echo "<ul>";
    echo "<li><strong>Type:</strong> {$carType}</li>";
    echo "<li><strong>Brand:</strong> {$brand}</li>";
    echo "<li><strong>Fuel:</strong> {$fuelType}</li>";
    echo "<li><strong>Seats:</strong> {$seats}</li>";
    echo "<li><strong>Mileage:</strong> {$mileage}</li>";
    echo "<li><strong>Price/Day:</strong> {$price}</li>";
    echo "<li><strong>Available:</strong> {$availability}</li>";
    echo "</ul>";
    if ($car['available']) {
        echo "<a href='reservation.php?vin={$car['vin']}' class='rent-btn'>Rent</a>";
    } else {
        echo "<button class='rent-btn' disabled>Unavailable</button>";
    }
    echo "</div>";
}
echo "</div>";
?>
