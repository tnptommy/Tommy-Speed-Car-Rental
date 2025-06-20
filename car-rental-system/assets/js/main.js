// main.js - fixed filtering logic
// Ensure filters and search box work together
// On page load, fetch all cars
document.addEventListener("DOMContentLoaded", function () {
    fetchFilteredCars();
});
document.addEventListener("DOMContentLoaded", function () {
    const searchBox = document.getElementById("searchBox");
    const carGrid = document.getElementById("carGrid");

    // Live keyword suggestion
    searchBox.addEventListener("input", function () {
        const keyword = this.value.trim();
        if (keyword.length < 2) {
            carGrid.innerHTML = ""; // Clear results if input is too short
            return;
        }

        fetch(`search.php?keyword=${encodeURIComponent(keyword)}`)
            .then(res => res.text())
            .then(html => {
                carGrid.innerHTML = html; // Update the car grid with the returned HTML
            })
            .catch(err => {
                console.error("Error fetching search results:", err);
                carGrid.innerHTML = "<p>Error loading search results.</p>";
            });
    });
});
