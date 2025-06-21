<?php
session_start();
include 'includes/header.php';
include 'includes/functions.php';

// Nếu là GET và có success, hiển thị thông báo xác nhận từ session
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['success'])) {
    if (isset($_SESSION['order_success'])) {
        $orderData = $_SESSION['order_success'];
        unset($_SESSION['order_success']); // Xóa để không lặp lại khi reload tiếp
        ?>
        <div class="confirm-container">
          <div class="confirm-card">
            <div class="confirm-success">
              <span class="icon">✔️</span>
              <span>Order confirmed!</span>
            </div>
            <div class="confirm-details">
              <div class="car-info">
                <img src="<?= htmlspecialchars($orderData['car']['image'] ?? 'assets/images/no-image.png') ?>" alt="<?= htmlspecialchars($orderData['car']['carModel']) ?>" class="car-img">
                <div class="car-meta">
                  <div class="car-title"><?= htmlspecialchars($orderData['car']['carModel']) ?> <span class="car-brand">(<?= htmlspecialchars($orderData['car']['brand']) ?>)</span></div>
                  <div class="car-row"><span class="car-label">Rental days:</span> <b><?= $orderData['rentalDays'] ?></b></div>
                  <div class="car-row"><span class="car-label">Quantity:</span> <b><?= $orderData['quantity'] ?></b></div>
                  <div class="car-row"><span class="car-label">Start date:</span> <b><?= htmlspecialchars($orderData['startDate']) ?></b></div>
                </div>
              </div>
              <div class="confirm-total">
                <span>Total Price:</span>
                <span class="total-price">$<?= number_format($orderData['total']) ?></span>
              </div>
            </div>
            <a href="index.php" class="confirm-link">Back to Home</a>
          </div>
        </div>
        <?php
    } else {
        // Không có dữ liệu đơn hàng, về home
        header("Location: index.php");
        exit;
    }
    include 'includes/footer.php';
    exit;
}

// Nếu không phải POST, về home
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Xử lý đặt hàng như cũ
$vin = $_POST['vin'] ?? null;
$name = $_POST['name'] ?? '';
$startDate = $_POST['startDate'] ?? '';
$rentalDays = (int) ($_POST['rentalPeriod'] ?? 0);
$quantity = (int) ($_POST['quantity'] ?? 1);

$cars = getAllCars();
$car = findCarByVIN($cars, $vin);

if ($car && $car['available'] && $car['availability'] >= $quantity) {
    $total = $car['pricePerDay'] * $rentalDays * $quantity;
    $order = [
      "customer" => ["name" => $name],
      "car" => ["vin" => $vin],
      "rental" => [
        "startDate" => $startDate,
        "rentalPeriod" => $rentalDays,
        "quantity" => $quantity,
        "totalPrice" => $total,
        "status" => "confirmed"
      ]
    ];
    saveOrder($order);
    updateCarAvailability($vin, $car['availability'] - $quantity);

    // Xóa lastSelectedCarVIN để không hiện lại xe vừa đặt ở reservation
    $userDataFile = 'data/user_data.json';
    if (file_exists($userDataFile)) {
        $userData = json_decode(file_get_contents($userDataFile), true);
        unset($userData['lastSelectedCarVIN']);
        file_put_contents($userDataFile, json_encode($userData, JSON_PRETTY_PRINT));
    }

    // Lưu thông tin đơn hàng vào session để hiển thị sau redirect
    $_SESSION['order_success'] = [
        'car' => $car,
        'rentalDays' => $rentalDays,
        'quantity' => $quantity,
        'startDate' => $startDate,
        'total' => $total
    ];
    header("Location: confirm_order.php?success=1");
    exit;
} else {
    ?>
    <div class="confirm-container">
      <div class="confirm-card">
        <div class="confirm-fail">
          <span class="icon">❌</span>
          <span>Order failed. Car unavailable or not enough quantity.</span>
        </div>
        <a href="reservation.php" class="confirm-link fail">Try Again</a>
      </div>
    </div>
    <?php
}
include 'includes/footer.php';

