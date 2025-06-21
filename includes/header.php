<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tommy Speed Car Rental</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
  <style>
    /* Dán đoạn CSS ở trên vào đây */
  </style>
</head>
<body>
  <header class="site-header">
    <div class="logo-container">
      <a href="index.php">
        <img src="assets/images/logo.png" alt="Tommy Speed Logo" class="logo">
      </a>
    </div>

    <form id="searchForm" style="position:relative; display:flex; align-items:center; gap:0;">
      <input type="text" id="searchBox" placeholder="Search cars..." autocomplete="off">
      <button type="submit" id="searchBtn" class="search-btn" aria-label="Search">
        <span class="search-icon">&#128269;</span>
      </button>
      <div id="suggestions"></div>
    </form>

    <a href="reservation.php" class="reservation-btn">
      🧾 Reservation
    </a>
  </header>

  <script>
const searchBox = document.getElementById('searchBox');
const suggestions = document.getElementById('suggestions');

searchBox.addEventListener('input', function() {
  const query = this.value.trim();
  if (query.length === 0) {
    suggestions.innerHTML = '';
    suggestions.style.display = 'none';
    return;
  }
  fetch('suggest.php?keyword=' + encodeURIComponent(query))
    .then(res => res.json())
    .then(data => {
      if (!data.length) {
        suggestions.innerHTML = '<div class="no-suggestion">No results</div>';
        suggestions.style.display = 'block';
        return;
      }
      suggestions.innerHTML = data.map(item => `
        <div class="suggestion-item" tabindex="0">
          ${item.icon ? `<img src="${item.icon}" class="suggestion-icon" alt="">` : ''}
          <span>${item.label.replace(new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'ig'), '<b>$1</b>')}</span>
        </div>
      `).join('');
      suggestions.style.display = 'block';
    });
});

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
  if (!searchBox.contains(e.target) && !suggestions.contains(e.target)) {
    suggestions.style.display = 'none';
  }
});

// Optional: Fill search box when clicking a suggestion
suggestions.addEventListener('click', function(e) {
  const item = e.target.closest('.suggestion-item');
  if (item) {
    searchBox.value = item.innerText;
    suggestions.style.display = 'none';
    searchBox.focus();
  }
});

document.getElementById('searchForm').addEventListener('submit', function(e) {
  e.preventDefault(); // Chặn reload trang
  const keyword = document.getElementById('searchBox').value.trim();
  // Gọi lại hàm AJAX search (ví dụ loadSearchResult)
  if (typeof loadSearchResult === 'function') {
    loadSearchResult(keyword);
  }
});

// function loadSearchResult(keyword) {
//   fetch('/car-rental-system/search.php?keyword=' + encodeURIComponent(keyword))
//     .then(res => res.text())
//     .then(html => {
//       // Đảm bảo bạn có 1 div với id="car-list-container" ở trang index.php
//       document.getElementById('car-list-container').innerHTML = html;
//     });
// }
</script>
</body>
</html>
