# 🛒 ShopX Global Marketplace (ShopX Global)

> **Premium Global Procurement, Freight Forwarding, and Import Management Platform**

ShopX Global Marketplace is a full-featured, enterprise-grade e-commerce & procurement platform built with a custom PHP MVC architecture. It offers seamless global shopping, virtual store management, digital wallet transactions, order tracking, and a powerful administrative control panel.

---

## ✨ Features & Modules

### 🛍️ E-Commerce & Marketplace
- **Catalog & Product Management**: Categorized products, hot deals, discounts, ratings, and instant search.
- **Shopping Cart & Checkout**: Persistent cart management (session & user-based) with streamlined checkout flow.
- **Wishlist**: Save favorite products for future purchases.
- **Virtual Stores**: Multi-vendor / merchant sub-storefront management.

### 💳 Payments & Wallet System
- **Digital Wallet**: Deposit funds, view transaction history, and pay directly using wallet balance.
- **Gift Cards**: Issue, purchase, and redeem digital gift cards.
- **Multiple Payment Gateways**: Modular support for local and international payment methods.

### 📦 Order & Logistics Management
- **Order Tracking**: End-to-end shipment & order status updates (`pending`, `processing`, `shipped`, `delivered`).
- **Invoicing**: Printable and downloadable customer invoices.
- **Warehouse & Freight Forwarding**: Manage quotes, warehouses, and international tracking records.

### 💬 Support & Customer Engagement
- **Live Support Chat**: Real-time messaging between users and support admins/bots.
- **Customer Dashboard**: Overview of recent orders, wallet balance, saved items, support tickets, and tracking.

### 🛡️ Admin Control Panel
- **Analytics & Reports**: Visual sales metrics and system performance metrics.
- **Customer Management**: User account administration, permissions, roles, and security audit logs.
- **Logistics Center**: Manage shipments, suppliers, tracking codes, and warehouse stock.

---

## 🛠️ Technology Stack

- **Backend**: Custom PHP MVC Framework (PHP 7.4+ / 8.x)
- **Database**: MySQL 5.7+ / MariaDB 10.3+ (PDO Driver)
- **Frontend**: HTML5, Vanilla CSS, JavaScript (ES6+), PWA enabled (`service-worker.js`, `manifest.json`)
- **Server Support**: Apache (`.htaccess` URL rewriting) / Nginx / Laragon / XAMPP

---

## 🚀 Getting Started

### Prerequisites

- **PHP**: 7.4 or higher (8.1+ recommended)
- **MySQL / MariaDB**: 5.7+ / 10.3+
- **Web Server**: Apache / Nginx (Laragon, XAMPP, or WAMP)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/xer-code/shop.git
   cd shop
   ```

2. **Configure Environment Variables**
   Copy `.env.example` to `.env` and configure your local database credentials:
   ```bash
   cp .env.example .env
   ```
   Update values in `.env`:
   ```ini
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=shopx_global
   DB_USER=root
   DB_PASS=
   APP_NAME="DexterX Global Marketplace"
   APP_URL="http://shop.test"
   APP_ENV=development
   ```

3. **Import Database Schema & Seed Data**
   Create the database and run the initial SQL files located in `database/`:
   ```bash
   mysql -u root -p shopx_global < database/schema.sql
   mysql -u root -p shopx_global < database/seed.sql
   ```
   *Or navigate to `http://localhost/shop/public/setup_db.php` in your browser if using local setup scripts.*

4. **Launch the Application**
   - **Laragon / Apache**: Point your virtual host root to `public/` or access `http://localhost/shop/public`.
   - **PHP Built-in Server**:
     ```bash
     php -S localhost:8000 -t public
     ```

---

## 📁 Directory Structure

```
├── app/
│   ├── Controllers/       # Application Logic (Shop, Cart, Order, Admin, etc.)
│   ├── Core/              # Custom MVC Engine (Router, Controller, Model, View, Auth)
│   ├── Models/            # Data Models (User, Product, Order, Wallet, etc.)
│   └── Views/             # HTML Templates & Layouts (Admin, Customer, Public)
├── config/                # Environment & App Configuration
├── database/              # Database Schema (schema.sql, seed.sql, migrations)
├── public/                # Publicly accessible assets (CSS, JS, Uploads, PWA)
│   ├── assets/            # CSS, JS, and Images
│   ├── index.php          # Front Controller Entry Point
│   └── manifest.json      # PWA Configuration
├── .env.example           # Template for Environment Configuration
├── .htaccess              # Apache URL Rewrite Rules
└── README.md              # Project Documentation
```

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
