<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/functions.php';

// Only use available cars for filters
$allCars = getAllCars();
$availableCars = array_filter($allCars, function($car) {
    return !empty($car['available']);
});
$carTypes = getUniqueValues($availableCars, 'carType');
$brands = getUniqueValues($availableCars, 'brand');
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const typeSelect = document.getElementById("filterType");
  const brandSelect = document.getElementById("filterBrand");
  const carGrid = document.getElementById("carGrid");
  const searchBox = document.getElementById("searchBox");

  const types = <?php echo json_encode($carTypes); ?>;
  const brands = <?php echo json_encode($brands); ?>;
  types.forEach(type => {
    const option = document.createElement("option");
    option.value = type;
    option.textContent = type;
    typeSelect.appendChild(option);
  });
  brands.forEach(brand => {
    const option = document.createElement("option");
    option.value = brand;
    option.textContent = brand;
    brandSelect.appendChild(option);
  });

  function fetchCars() {
    const type = typeSelect.value;
    const brand = brandSelect.value;
    const keyword = searchBox ? searchBox.value.trim() : "";
    const url = `search.php?keyword=${encodeURIComponent(keyword)}&type=${encodeURIComponent(type)}&brand=${encodeURIComponent(brand)}`;
    fetch(url)
      .then(res => res.text())
      .then(html => {
        carGrid.innerHTML = html;
      });
  }

  typeSelect.addEventListener("change", fetchCars);
  brandSelect.addEventListener("change", fetchCars);
  if (searchBox) searchBox.addEventListener("input", fetchCars);

  // Gọi fetchCars khi trang vừa load để hiển thị danh sách ban đầu
  fetchCars();

  // Khi submit search form (từ header), cũng gọi fetchCars
  const searchForm = document.getElementById("searchForm");
  if (searchForm) {
    searchForm.addEventListener("submit", function(e) {
      e.preventDefault();
      fetchCars();
    });
  }
});
</script>

<div class="container modern-container">
  <section class="filters-section">
    <div class="filters">
      <select id="filterType">
        <option value="">All Types</option>
      </select>
      <select id="filterBrand">
        <option value="">All Brands</option>
      </select>
    </div>
  </section>

  <section>
    <div id="carGrid" class="car-grid">
      <?php include 'includes/loadCars.php'; ?>
    </div>
  </section>
</div>

<script src="assets/js/main.js"></script>
<?php include 'includes/footer.php'; ?>
