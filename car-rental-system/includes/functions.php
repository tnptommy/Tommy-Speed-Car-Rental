<?php
function getAllCars() {
  $file = __DIR__ . '/../data/cars.json';
  if (!file_exists($file)) return [];
  $json = file_get_contents($file);
  $data = json_decode($json, true);
  return $data['cars'] ?? [];
}

function getUniqueValues($cars, $key) {
  $values = array_column($cars, $key);
  $unique = array_unique($values);
  sort($unique);
  return $unique;
}

function findCarByVIN($cars, $vin) {
  foreach ($cars as $car) {
    if ($car['vin'] === $vin) return $car;
  }
  return null;
}

function saveOrder($order) {
  $file = __DIR__ . '/../data/orders.json';
  $orders = file_exists($file) ? json_decode(file_get_contents($file), true) : ['orders' => []];
  $orders['orders'][] = $order;
  file_put_contents($file, json_encode($orders, JSON_PRETTY_PRINT));
}

function updateCarAvailability($vin, $newAvailability) {
  $file = __DIR__ . '/../data/cars.json';
  $data = json_decode(file_get_contents($file), true);
  foreach ($data['cars'] as &$car) {
    if ($car['vin'] === $vin) {
      $car['availability'] = $newAvailability;
      $car['available'] = $newAvailability > 0 ? true : false;
      break;
    }
  }
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}