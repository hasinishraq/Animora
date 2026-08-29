<h1 align="center">
  🐾 Animora
</h1>

<p align="center">
  <em>A comprehensive pet welfare platform — adoption, rescue, marketplace, services & more</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8+-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-DB-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Pattern-MVC-green?style=for-the-badge" />
  <img src="https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge" />
</p>

---

## 📖 Overview

**Animora** is a full-featured pet welfare web application built with PHP following the MVC (Model-View-Controller) architectural pattern. It serves as a unified hub for pet adoption, rescue reporting, pet-related product marketplace, veterinary services, donations, and real-time messaging — all under one roof.

Whether you are a pet lover looking to adopt, a volunteer helping with rescues, a vet offering services, or a supplier listing products, Animora provides a tailored experience for every role.

---

## ✨ Features

### 🐶 Pet Adoption
- Browse available pets for adoption
- Post pets for adoption

### 🚨 Rescue Operations
- Report animals in distress
- Manage and coordinate rescue missions (volunteer/admin)

### 🛒 Marketplace
- Browse and purchase pet products
- Suppliers can list and manage products
- Order management system with cart support

### 🏥 Pet Services
- Book veterinary and grooming services
- Vets and petcare providers can manage their service listings

### 💰 Donations & Fundraising
- Donate to animal welfare causes
- Create and manage fundraising campaigns

### 💬 Messaging
- In-platform chat between users, vets, volunteers, and admins

### 👤 Multi-Role User System
| Role | Dashboard |
|------|-----------|
| **User** | Adopt pets, book services, shop, donate |
| **Admin** | Full platform management |
| **Volunteer** | Manage rescue operations |
| **Vet** | Manage and offer services |
| **Pet Care Provider** | Manage pet care services |
| **Supplier** | Manage products and orders |

---

## 🏗️ Project Structure

```
Animora/
├── index.php                  # Application entry point
├── config/
│   ├── database.php           # Database connection
│   └── constants.php          # App-wide constants
├── routes/
│   └── web.php                # Route definitions
├── middleware/
│   └── authMiddleware.php     # Authentication middleware
├── controllers/
│   ├── auth/                  # Login, Register, Logout
│   ├── adoption/              # Browse & post pets
│   ├── rescue/                # Report & manage rescues
│   ├── marketplace/           # Products, orders
│   ├── services/              # Book & manage services
│   ├── donations/             # Donate & fundraising
│   ├── messaging/             # Chat
│   └── dashboard/             # Role-based dashboards
├── models/
│   ├── User.php
│   ├── Pet.php
│   ├── Product.php
│   ├── Order.php
│   ├── Cart.php
│   ├── Service.php
│   ├── Rescue.php
│   └── Donation.php
├── views/
│   ├── auth/                  # Login & Register pages
│   ├── dashboard/             # Role-specific home pages
│   └── includes/              # Shared partials (header, navbar, footer)
└── assets/
    ├── css/                   # Stylesheets
    ├── js/                    # JavaScript files
    └── images/                # Static images
```

---

## 🚀 Getting Started

### Prerequisites

- PHP **8.0+**
- MySQL / MariaDB
- A local server environment (e.g., [XAMPP](https://www.apachefriends.org/), [WAMP](https://www.wampserver.com/), or [Laragon](https://laragon.org/))

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/hasinishraq/Animora.git
   cd Animora
   ```

2. **Move to your server web root**
   - XAMPP: `C:/xampp/htdocs/Animora`
   - Laragon: `C:/laragon/www/Animora`

3. **Create the database**
   - Open **phpMyAdmin** and create a new database named `animora`
   - Import the SQL schema (if provided)

4. **Configure the database connection**
   - Open `config/database.php` and update the credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'animora');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

5. **Run the app**
   - Start Apache & MySQL from your server control panel
   - Visit: `http://localhost/Animora`

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 8+ (custom MVC) |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Architecture** | MVC (Model-View-Controller) |
| **Auth** | Session-based with middleware |

---

## 🤝 Contributing

Contributions are welcome! To get started:

1. Fork the repository
2. Create a new branch: `git checkout -b feature/your-feature-name`
3. Make your changes and commit: `git commit -m "Add some feature"`
4. Push to the branch: `git push origin feature/your-feature-name`
5. Open a Pull Request

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

<p align="center">Made with ❤️ for animals everywhere 🐾</p>
