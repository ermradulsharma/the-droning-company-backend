# The Droning Company - Backend

[![Laravel Version](https://img.shields.io/badge/Laravel-8.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%5E7.3%20%7C%20%5E8.0-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Backend API and Administrative Dashboard for **The Droning Company**, a comprehensive ecosystem for drone pilots and clients. Built with Laravel 8, this platform manages user profiles, drone job listings, subscriptions, and rich media galleries.

---

## 🚀 Tech Stack

- **Framework:** [Laravel 8](https://laravel.com/)
- **PHP Version:** ^7.3 | ^8.0
- **Database:** MySQL / MariaDB
- **Key Packages:**
    - `laravel/cashier`: Seamless Stripe integration for subscriptions.
    - `spatie/laravel-medialibrary`: Advanced media management for pilot galleries.
    - `livewire/livewire`: Modern, dynamic reactive interfaces.
    - `yajra/laravel-datatables-oracle`: High-performance data tables for admin views.
    - `laravel/sanctum`: Lightweight API token authentication.
    - `laravel/telescope`: Essential debugging and monitoring tool.
    - `plesk/ext-laravel-integration`: Optimized for Plesk hosting environments.

---

## ✨ Features

- **User Management:** Detailed profiles for Drone Pilots and Clients with role-based access control.
- **Subscription Engine:** Flexible subscription plans powered by Laravel Cashier (Stripe).
- **Job Marketplace:** A centralized hub where pilots can discover and apply for drone-related projects.
- **Media Showcase:** High-performance galleries for pilots to display high-resolution images and videos.
- **Content Engine:** Integrated Blog, FAQ, and dynamic page management systems.
- **Admin Dashboard:** A robust administrative interface to oversee the entire platform ecosystem.

---

## 🛠️ Getting Started

### Prerequisites

- PHP ^7.3 or ^8.0
- [Composer](https://getcomposer.org/)
- MySQL/MariaDB
- Node.js & NPM

### Installation Steps

1. **Clone the repository:**

    ```bash
    git clone https://github.com/ermradulsharma/the-droning-company-backend.git
    cd the-droning-company-backend
    ```

2. **Install PHP dependencies:**

    ```bash
    composer install
    ```

3. **Install Node dependencies:**

    ```bash
    npm install
    ```

4. **Environment Configuration:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Note: Remember to configure your Database and Stripe credentials in the `.env` file._

5. **Database Setup:**

    ```bash
    php artisan migrate --seed
    ```

6. **Storage Link:**

    ```bash
    php artisan storage:link
    ```

7. **Compile Assets:**

    ```bash
    npm run dev
    ```

8. **Start Development Server:**
    ```bash
    php artisan serve
    ```

---

## 🧪 Testing & Documentation

### Running Tests

Ensure the quality of the codebase by running the test suite:

```bash
php artisan test
```

### API Documentation

Generate and view the latest API documentation using [Scribe](https://scribe.knuckles.wtf/):

```bash
php artisan scribe:generate
```

---

## 🤝 Community & Support

- [Contributing Guide](CONTRIBUTING.md)
- [Code of Conduct](CODE_OF_CONDUCT.md)
- [Security Policy](SECURITY.md)
- [Support Information](SUPPORT.md)

---

## 📜 License

This project is licensed under the [MIT License](LICENSE).
