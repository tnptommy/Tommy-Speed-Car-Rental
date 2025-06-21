# 🚗 Tommy Speed Car Rental System

**Tommy Speed** is my project developed as part of the *Internet Programming* course.

This web application simulates a real-world car rental system where users can browse, search, filter, and reserve cars easily and efficiently. The user interface is clean and modern, and most interactions are handled using AJAX for a smooth, dynamic experience without page reloads.

🌐 **Live Demo:** [http://tommyspeedbrand.store](http://tommyspeedbrand.store)  
🐳 **Deployment:** Docker container running on an Ubuntu EC2 instance (AWS)

<img width="1257" alt="image" src="https://github.com/user-attachments/assets/89601afe-6281-45b5-929d-9adefb848a5f" />

---

## 🧑‍💻 Technologies Used

- **HTML5 / CSS3** for the front-end structure and styling  
- **JavaScript** for dynamic behavior on the client side  
- **PHP** for server-side processing  
- **JSON** for data storage (simulating a database)  
- **AJAX** for asynchronous updates and seamless user interaction  
- **Docker** for containerized deployment  
- **NGINX** as a reverse proxy server  
- **AWS EC2** for hosting  

---

## 🔍 Features

- **Homepage Grid:** Cars are displayed in a clean grid layout with key info.  
- **Live Search:** Real-time keyword suggestions powered by `search_suggestions.php`.  
- **Filters:** Users can filter by type and brand using `filterCars.php`.  
- **AJAX-Powered UI:** No page reloads – just smooth interactions.  
- **Reservation Form:** Automatically prefilled if user input was saved.  
- **Order Confirmation:** Finalize rentals with `confirm_order.php`.  
- **Data-Driven:** Car and order info stored in JSON files.  

---

## 🗂 Project Structure



```
CAR-RENTAL-SYSTEM/
│
├── api/
│   └── search_suggestions.php        # Real-time search suggestion logic
│
├── assets/
│   ├── css/
│   │   └── style.css                 # Main stylesheet
│   ├── images/                       # Car images & icons
│   └── js/
│       ├── main.js                   # Handles UI logic, search, filtering
│       └── updateAvailability.js     # Updates car availability dynamically
│
├── data/
│   ├── cars.json                     # Car listings
│   ├── orders.json                   # Rental order records
│   └── user_data.json                # Temporarily stored user form input
│
├── includes/
│   ├── filterCars.php                # Filter logic based on search criteria
│   ├── footer.php                    # Shared footer
│   ├── functions.php                 # Helper PHP functions
│   ├── header.php                    # Shared header with nav/logo
│   └── loadCars.php                  # Load/display cars from JSON
│
├── scripts/
│   └── updateAvailability.js         # (Optional extra location for shared JS)
│
├── index.php                         # Homepage with grid view
├── reservation.php                   # Reservation form + logic
├── search.php                        # Processes search queries
├── suggest.php                       # Suggestion API endpoint
├── confirm_order.php                 # Handles order confirmation
├── clear_user_data.php              # Clears saved form data
└── favicon.ico                       # Site icon
```

---

## 🚀 Getting Started

### Requirements

- PHP 7.4+  
- Local server like XAMPP/WAMP or cloud (AWS Elastic Beanstalk works great)

### Local Setup

1. Place the folder in your local server’s root (e.g., `htdocs`).
2. Open your browser and go to `localhost/CAR-RENTAL-SYSTEM/index.php`.
3. Make sure `data/` is writable for saving orders/user data.

---

## ✅ How It Works

- **Browse:** Start at the homepage.
- **Search & Filter:** Find your car using the top search box or filters.
- **Reserve:** Click “Rent,” fill in your details, confirm the order.
- **Order Management:** The system updates availability and saves your order.

---

## 📌 Notes

- Saved user form data is stored in `user_data.json`.
- VINs are hidden from users but used internally to track cars.
- Reservation logic checks availability before allowing booking.

---

## 👥 Credits

Built with ❤️ by the me (Tommy).  
Icons and images are for demo purposes only.

---

## 📜 License

For demo/educational use only. Replace demo content before real-world deployment.
