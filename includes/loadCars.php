<?php
include_once __DIR__ . '/functions.php';

$cars = getAllCars();

foreach ($cars as $car): ?>
  <div class="car-card" data-brand="<?= $car['brand'] ?>" data-type="<?= $car['carType'] ?>">
    <img src="<?= $car['image'] ?>" alt="<?= htmlspecialchars($car['carModel']) ?>">
    <h3><?= htmlspecialchars($car['carModel']) ?></h3>
    <p><?= htmlspecialchars($car['description']) ?></p>
    <ul>
      <li><strong>Type:</strong> <?= $car['carType'] ?></li>
      <li><strong>Brand:</strong> <?= $car['brand'] ?></li>
      <li><strong>Fuel:</strong> <?= $car['fuelType'] ?></li>
      <li><strong>Seats:</strong> <?= $car['seats'] ?></li>
      <li><strong>Mileage:</strong> <?= $car['mileage'] ?></li>
      <li><strong>Price/Day:</strong> $<?= $car['pricePerDay'] ?></li>
      <li><strong>Available:</strong> <?= $car['available'] ? $car['availability'] : 0 ?></li>
    </ul>
    <?php if ($car['available']): ?>
      <a href="reservation.php?vin=<?= $car['vin'] ?>" class="rent-btn">Rent</a>
    <?php else: ?>
      <button class="rent-btn" disabled>Unavailable</button>
    <?php endif; ?>
  </div>
<?php endforeach; ?>