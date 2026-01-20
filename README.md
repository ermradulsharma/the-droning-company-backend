# The Droning Company - Backend

Backend API and Administrative Dashboard for The Droning Company, built with Laravel 8.

## Tech Stack

- **Framework:** [Laravel 8](https://laravel.com/)
- **PHP Version:** ^7.3 | ^8.0
- **Database:** MySQL / MariaDB
- **Key Packages:**
    - `laravel/cashier`: Stripe integration for subscriptions and payments.
    - `spatie/laravel-medialibrary`: Advanced media management.
    - `livewire/livewire`: Dynamic interfaces for the admin panel.
    - `yajra/laravel-datatables-oracle`: Powering data-heavy tables.
    - `laravel/sanctum`: API token authentication.
    - `laravel/telescope`: Debugging and monitoring.
    - `plesk/ext-laravel-integration`: Optimized for Plesk hosting.

## Features

- **User Management:** Pilot and Client profiles, role-based access control.
- **Subscription System:** Powered by Laravel Cashier (Stripe).
- **Project/Job Management:** Pilots can find and apply for drone jobs.
- **Media Management:** Gallery for pilots to showcase their work (images/videos).
- **Content Management:** Blogs, FAQs, and dynamic pages.
- **Administrative Tools:** Full-featured dashboard for managing the ecosystem.

## Getting Started

### Prerequisites

- PHP ^7.3 or ^8.0
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Installation

1. **Clone the repository:**

    ```bash
    git clone <repository-url>
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

4. **Environment Setup:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Configure your database and Stripe credentials in the `.env` file._

5. **Run Migrations & Seeders:**

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

8. **Start the server:**
    ```bash
    php artisan serve
    ```

## Testing

Run the test suite using:

```bash
php artisan test
```

## Documentation

API documentation can be generated/viewed using [Scribe](https://scribe.knuckles.wtf/):

```bash
php artisan scribe:generate
```

## License

This project is proprietary and confidential.
