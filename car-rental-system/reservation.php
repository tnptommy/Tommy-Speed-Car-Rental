<?php
include 'includes/header.php';
include 'includes/functions.php';

$cars = getAllCars();
$vin = $_GET['vin'] ?? null;
$userDataFile = 'data/user_data.json';
$userData = file_exists($userDataFile) ? json_decode(file_get_contents($userDataFile), true) : [];
$prefill = $userData['previousInputs'] ?? [];
$lastSelectedVin = $userData['lastSelectedCarVIN'] ?? null;

// Nếu có VIN trên URL thì lấy xe đó, nếu không thì lấy xe cuối cùng đã chọn
if ($vin) {
    $selectedCar = findCarByVIN($cars, $vin);
    $userData['lastSelectedCarVIN'] = $vin;
    file_put_contents($userDataFile, json_encode($userData, JSON_PRETTY_PRINT));
} elseif ($lastSelectedVin) {
    $selectedCar = findCarByVIN($cars, $lastSelectedVin);
} else {
    $selectedCar = null;
}
?>

<div class="reservation-container">
  <div class="reservation-header">
    <h2>Reservation Page</h2>
  </div>

  <?php if ($selectedCar): ?>
    <div class="car-summary">
      <div class="car-summary-info">
        <h3><?= htmlspecialchars($selectedCar['carModel']) ?></h3>
        <ul>
          <li><strong>Brand:</strong> <?= htmlspecialchars($selectedCar['brand']) ?></li>
          <li><strong>Type:</strong> <?= htmlspecialchars($selectedCar['carType']) ?></li>
          <li><strong>Price/day:</strong> $<?= htmlspecialchars($selectedCar['pricePerDay']) ?></li>
          <li><strong>Available:</strong> <?= htmlspecialchars($selectedCar['availability'] ?? 0) ?></li>
        </ul>
      </div>
      <div class="car-summary-image">
        <img src="<?= htmlspecialchars($selectedCar['image'] ?? 'assets/images/no-image.png') ?>" alt="<?= htmlspecialchars($selectedCar['carModel']) ?>" style="max-width:180px;max-height:120px;border-radius:8px;">
      </div>
    </div>

    <?php if ($selectedCar['available']): ?>
      <form id="reservationForm" class="reservation-form" method="POST" action="confirm_order.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($prefill['name'] ?? '') ?>" required>
        <div class="input-feedback" id="nameFeedback"></div>

        <label for="phoneNumber">Phone:</label>
        <input type="tel" id="phoneNumber" name="phoneNumber" value="<?= htmlspecialchars($prefill['phoneNumber'] ?? '') ?>" required>
        <div class="input-feedback" id="phoneFeedback"></div>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($prefill['email'] ?? '') ?>" required>
        <div class="input-feedback" id="emailFeedback"></div>

        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" value="<?= htmlspecialchars($prefill['startDate'] ?? '') ?>" required>
        <div class="input-feedback" id="dateFeedback"></div>

        <label for="rentalPeriod">Rental Days:</label>
        <input type="number" id="rentalPeriod" name="rentalPeriod" value="<?= htmlspecialchars($prefill['rentalPeriod'] ?? 1) ?>" min="1" required>
        <div class="input-feedback" id="daysFeedback"></div>

        <input type="hidden" name="vin" value="<?= htmlspecialchars($selectedCar['vin']) ?>">

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= htmlspecialchars($selectedCar['availability'] ?? 1) ?>" required>
        <div class="input-feedback" id="quantityFeedback"></div>

        <div class="total-price" id="totalPrice">
          Total Price: $<?= $selectedCar['pricePerDay'] ?>
        </div>

        <div class="form-actions">
          <button type="button" class="back-btn" onclick="window.history.back()">Back</button>
          <button type="button" class="cancel-btn" onclick="handleCancel()">Cancel</button>
          <button type="submit" class="submit-btn" id="submitBtn" disabled>Submit</button>
        </div>
      </form>
      <div id="noCarMsg" style="display:none; margin-top:32px; text-align:center; color:#b30000; font-size:1.2em;">
        No car selected. Please choose a car first!
      </div>
    <?php else: ?>
      <p>This car is no longer available.</p>
    <?php endif; ?>
  <?php else: ?>
  <div id="noCarMsgDefault" style="margin-top:32px; text-align:center; color:#b30000; font-size:1.2em;">
    No car selected. Please choose a car first!
  </div>
  <?php endif; ?>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const rentalPeriodInput = document.getElementById("rentalPeriod");
    const quantityInput = document.getElementById("quantity");
    const totalPriceElement = document.getElementById("totalPrice");
    const pricePerDay = <?= $selectedCar['pricePerDay'] ?? 0 ?>;
    const form = document.getElementById("reservationForm");
    const submitBtn = document.getElementById("submitBtn");

    function updateTotal() {
      const rentalDays = parseInt(rentalPeriodInput.value) || 0;
      const quantity = parseInt(quantityInput.value) || 0;
      const totalPrice = rentalDays > 0 && quantity > 0 ? rentalDays * pricePerDay * quantity : 0;
      totalPriceElement.textContent = `Total Price: $${totalPrice}`;
    }

    function checkFormValidity() {
      if (form.checkValidity()) {
        submitBtn.disabled = false;
      } else {
        submitBtn.disabled = true;
      }
    }

    // Kiểm tra mỗi khi nhập liệu
    form.addEventListener("input", checkFormValidity);
    // Kiểm tra khi load trang (nếu có prefill)
    checkFormValidity();

    if (rentalPeriodInput && quantityInput) {
      rentalPeriodInput.addEventListener("input", updateTotal);
      quantityInput.addEventListener("input", updateTotal);
    }

    // Live feedback for each input
    const nameInput = document.getElementById("name");
    const phoneInput = document.getElementById("phoneNumber");
    const emailInput = document.getElementById("email");
    const dateInput = document.getElementById("startDate");
    const daysInput = rentalPeriodInput; // đã có ở trên

    nameInput.addEventListener("input", function() {
      const feedback = document.getElementById("nameFeedback");
      if (nameInput.value.trim().length < 2) {
        feedback.textContent = "Name must be at least 2 characters.";
        feedback.classList.remove("valid");
      } else {
        feedback.textContent = "";
        feedback.classList.remove("valid");
      }
    });

    phoneInput.addEventListener("input", function() {
      const feedback = document.getElementById("phoneFeedback");
      const phonePattern = /^[0-9\-\+\s]{8,}$/;
      feedback.textContent = !phonePattern.test(phoneInput.value.trim()) ? "Enter a valid phone number." : "";
    });

    emailInput.addEventListener("input", function() {
      const feedback = document.getElementById("emailFeedback");
      feedback.textContent = emailInput.validity.typeMismatch ? "Enter a valid email address." : "";
    });

    dateInput.addEventListener("input", function() {
      const feedback = document.getElementById("dateFeedback");
      feedback.textContent = !dateInput.value ? "Please select a start date." : "";
    });

    daysInput.addEventListener("input", function() {
      const feedback = document.getElementById("daysFeedback");
      feedback.textContent = daysInput.value < 1 ? "Rental days must be at least 1." : "";
    });

    quantityInput.addEventListener("input", function() {
      const feedback = document.getElementById("quantityFeedback");
      const maxAvailable = parseInt(quantityInput.max, 10) || 1;
      const quantity = parseInt(quantityInput.value, 10) || 0;

      if (quantity < 1) {
        feedback.textContent = "Quantity must be at least 1.";
        feedback.classList.remove("valid");
      } else if (quantity > maxAvailable) {
        feedback.textContent = `Only ${maxAvailable} car(s) available.`;
        feedback.classList.remove("valid");
      } else {
        feedback.textContent = ""; // Xóa thông báo khi hợp lệ
        feedback.classList.add("valid");
      }
    });
  });

  function handleCancel() {
    // Clear user data on the server and redirect to homepage
    fetch('clear_user_data.php', { method: 'POST' })
      .then(() => {
        window.location.href = 'index.php'; // Redirect to homepage
      });
  }
</script>

<?php include 'includes/footer.php'; ?>