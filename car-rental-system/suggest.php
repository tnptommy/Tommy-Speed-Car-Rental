<?php

include 'includes/functions.php';

header('Content-Type: application/json');
$keyword = strtolower(trim($_GET['keyword'] ?? ''));
$cars = getAllCars();

$suggestions = [];
if ($keyword !== '') {
    $unique = [];
    foreach ($cars as $car) {

        if (!empty($car['brand'])) {
            $brand = $car['brand'];
            if (stripos($brand, $keyword) !== false && !isset($unique['brand_'.$brand])) {
                $suggestions[] = [
                    'type' => 'brand',
                    'label' => $brand,
                    'icon' => '' 
                ];
                $unique['brand_'.$brand] = true;
            }
        }
 
        if (!empty($car['brand']) && !empty($car['carModel'])) {
            $brandModel = $car['brand'] . ' ' . $car['carModel'];
            if (stripos($brandModel, $keyword) !== false && !isset($unique['model_'.$brandModel])) {
                $suggestions[] = [
                    'type' => 'brand_model',
                    'label' => $brandModel,
                    'icon' => !empty($car['image']) ? $car['image'] : ''
                ];
                $unique['model_'.$brandModel] = true;
            }
        }
    }
}
echo json_encode(array_slice($suggestions, 0, 12));