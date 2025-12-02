# Tecomp99 – E-Commerce & Onsite Computer Repair Service

Tecomp99 is a full-featured e-commerce platform designed for selling computer products and providing onsite computer repair services. The system supports seamless product purchasing, structured home-service booking, service scheduling, and complete customer profile management.

## 🚀 Features

### 🛒 E-Commerce

-   Browse and buy computer products
-   Product detail pages, stock display, and variants
-   Cart & checkout flow
-   Order history and tracking

### 🛠️ Onsite Computer Repair Service

-   Order onsite computer repair service to home
-   Submit device type, issue details, and media files (photos/videos)
-   Multi-file upload with automatic compression
-   Automatic service flow per order status

### 📅 Smart Scheduling System

-   6 available time slots per day
-   Maximum 4 orders per slot
-   Real-time availability checking
-   Prevents overbooking

### 👤 Customer Management

-   Customer profile & multiple address support
-   Address validation before booking service
-   Integrated login/register system
-   Service and product order history

### 📊 Admin Dashboard

-   Manage products, categories, and inventory
-   Manage service orders & schedule availability
-   Manage customers and addresses
-   Media file management
-   Internal status updates and notifications

## 🧱 Tech Stack

### Backend

-   Laravel 11
-   PHP
-   JavaScript

### Frontend

-   Blade
-   Tailwind CSS
-   Flowbite
-   Interactive Components
-   Livewire

### Database

-   MySQL

### Others

-   Storage-based file handling
-   Authentication & authorization system
-   File compression for uploaded media

## 📂 Project Structure (High-Level)

```
/app
  /Http
    /Controllers
    /Livewire
  /Models

/resources
  /views
  /livewire

/routes
  web.php

/storage
  /app
    /public
      /order_service/{id}/media-*
```

## ⚙️ Installation

### Requirements

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   MySQL
-   Laravel 11

### Steps

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/tecomp99.git
    cd tecomp99
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install Node.js dependencies:

    ```bash
    npm install
    ```

4. Copy the environment file and configure it:

    ```bash
    cp .env.example .env
    ```

    Edit `.env` to set your database credentials and other configurations.

5. Generate application key:

    ```bash
    php artisan key:generate
    ```

6. Run database migrations:

    ```bash
    php artisan migrate
    ```

7. (Optional) Seed the database with sample data:

    ```bash
    php artisan db:seed
    ```

8. Build frontend assets:

    ```bash
    npm run build
    ```

    Or for development:

    ```bash
    npm run dev
    ```

9. Serve the application:
    ```bash
    php artisan serve
    ```

The application will be available at `http://localhost:8000`.

## 📌 Usage Guide

### Customer Side

-   Browse and purchase computer products
-   Create an account or log in
-   Add delivery address
-   Order onsite computer repair service
-   Select available schedule
-   Upload photos/videos of the issue
-   Track order status (product or service)

### Admin Side

-   Manage products, categories, and services
-   Monitor daily service slots
-   Approve/decline service requests
-   Manage customer information
-   Handle internal order status updates

## 📄 License

MIT License

## 👤 Author

Maulana Bryan Syahputra
