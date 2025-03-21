# Currency Exchange

## Overview
This project is a Laravel 10-based application with authentication, external API integration, and a scheduled task system. It uses Filament for the admin panel and JWT tokens for authentication.

## Tech Stack
- **Backend:** Laravel 10, PHP 8.1, Composer
- **Authentication:** JWT Token
- **Database Management:** Migrations, Seeders
- **Task Scheduling:** Cron Jobs (Scheduler)
- **Admin Panel:** Filament
- **External Integrations:** External API
- **Frontend:** HTML, CSS, JavaScript

## Installation
### Prerequisites
Ensure you have the following installed:
- PHP 8.1+
- Composer
- Laravel 10
- MySQL or any preferred database
- CronTab

### Setup Steps
1. **Clone the Repository:**
   ```sh
   git clone https://github.com/adrian-bulat/CurrencyExchangeRates.git
   cd CurrencyExchangeRates
   ```
2. **Install Dependencies:**
   ```sh
   composer install
   ```
3. **Environment Setup:**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
4. **Run Migrations & Seeders:**
   ```sh
   php artisan migrate --seed
   ```
5. **Generate JWT Secret Key:**
   ```sh
   php artisan jwt:secret
   ```
6. **Start the Application:**
   ```sh
   php artisan serve
   ```

## Usage
- **Admin Panel:** `/admin` (secured with Filament authentication)
- **User Registration:** Access `/register` to begin interacting with the application.
- **User Login:** If you already have credentials, access `/login`.

### Scheduler Setup

**Install crontab**
   ```sh
    sudo apt update
    sudo apt install crontab
    sudo systemctl start cron
    sudo systemctl status cron
   ```

To run scheduled tasks, add this cron job:
**Config logging**
   ```sh
    crontab -e
    * * * * * php /*path-to-project*/artisan schedule:run >> /*path-to-project*/storage/logs/schedule.log 2>&1
   ```
**Ensure Artisan Commands Are Available**
   ```sh
    /usr/bin/php /*path-to-project*/artisan schedule:run
   ```

## API Integration
This project fetches data from an external API. Configure API key in the `.env` file with provided one or your own.
   ```sh
    EXCHANGE_RATE_API_KEY=
   ```
To obtain your own API Key you can register on API provider website https://apilayer.com/ 

