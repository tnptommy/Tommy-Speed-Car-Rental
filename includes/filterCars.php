<?php

include 'functions.php';

$type = $_GET['type'] ?? '';
$brand = $_GET['brand'] ?? '';
$keyword = strtolower(trim($_GET['keyword'] ?? ''));

$cars = getAllCars();
$filtered = array_filter($cars, function($car) use ($type, $brand, $keyword) {
    if (!empty($type) && (!isset($car['carType']) || $car['carType'] !== $type)) return false;
    if (!empty($brand) && (!isset($car['brand']) || $car['brand'] !== $brand)) return false;
    if (!empty($keyword)) {
        $model = strtolower(trim($car['carModel'] ?? ''));
        $carBrand = strtolower(trim($car['brand'] ?? ''));
        $carType = strtolower(trim($car['carType'] ?? ''));
        $desc = strtolower(trim($car['description'] ?? ''));
        $brandModel = trim($carBrand . ' ' . $model);
        $matchKeyword = strpos($model, $keyword) !== false ||
            strpos($carBrand, $keyword) !== false ||
            strpos($carType, $keyword) !== false ||
            strpos($desc, $keyword) !== false ||
            strpos($brandModel, $keyword) !== false;
        if (!$matchKeyword) return false;
    }
    return isset($car['available']) && $car['available'];
});

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
    echo "<h3>{$model}</h3>";
    echo "<p>{$car['description']}</p>";
    echo "<ul>";
    echo "<li><strong>Type:</strong> {$carType}</li>";
    echo "<li><strong>Brand:</strong> {$brand}</li>";
    echo "<li><strong>Fuel:</strong> {$fuelType}</li>";
    echo "<li><strong>Seats:</strong> {$seats}</li>";
    echo "<li><strong>Mileage:</strong> {$mileage}</li>";
    echo "<li><strong>Price/Day:</strong> {$price}</li>";
    echo "<li><strong>Available:</strong> {$availability}</li>";
    echo "</ul>";
    if (!empty($car['available'])) {
        echo "<a href='reservation.php?vin={$car['vin']}' class='rent-btn'>Rent</a>";
    } else {
        echo "<button class='rent-btn' disabled>Unavailable</button>";
    }
    echo "</div>";
}
?>