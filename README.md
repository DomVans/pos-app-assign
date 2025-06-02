Point of Sale (POS) System
Overview

This is a simple Point of Sale (POS) system created by Biakropuia MZU as part of the fulfillment of campus recruitment by Lailen Pvt Ltd. The project was built within a week using Filament and Laravel.


Technologies Used

    Laravel - Backend framework
    Filament - Admin panel and UI components
    Tailwind.css - custom pages

Features

    Product & Inventory Management
    Point of Sale Interface
    Secure Login and Authentication
    Sales tracking and reports

Installation
Prerequisites:

    PHP 8.0 or higher
    Composer
    PostgreSQL

Steps to Install:
Clone the repository:

git clone https://github.com/domvans/pos-app-assign.git
cd pos

Install dependencies:

composer install

Copy environment file and configure database:

cp .env.example .env

Update .env with your database credentials.
Generate application key:

php artisan key:generate

Run database migrations:

php artisan migrate --seed

Serve the application:

php artisan serve

Login Credentials(After seed)

Email:admin@admin.com 
Password: password
