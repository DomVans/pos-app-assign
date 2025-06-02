 Point of Sale (POS) System

A simple yet functional Point of Sale (POS) system built using Laravel and Filament, developed by Dominic Vansangzuala as part of the campus recruitment program for Lailen Pvt. Ltd. This project was completed within one week.
🛠️ Technologies Used

    Laravel – PHP web application framework

    Filament – Admin panel and UI components

    Tailwind CSS – Utility-first CSS framework for custom pages

    PostgreSQL – Database

✨ Features

    ✅ Product & Inventory Management

    ✅ Point of Sale Interface (custom Filament page)

    ✅ Secure Login & Authentication

    ✅ Sales Tracking & Reporting

🚀 Installation Guide
✅ Prerequisites

Ensure you have the following installed:

    PHP 8.0 or higher

    Composer

    PostgreSQL

📥 Clone the Repository

git clone https://github.com/domvans/pos-app-assign.git
cd pos-app-assign

📦 Install Dependencies

composer install

npm install

npm run dev

⚙️ Configure Environment

cp .env.example .env

Edit your .env file and update the database credentials accordingly.
🔑 Generate App Key

php artisan key:generate

🗃️ Run Migrations and Seeders

php artisan migrate --seed

▶️ Serve the Application

php artisan serve

🔐 Default Login Credentials

After running the seeders, you can log in with:

    Email: admin@admin.com

    Password: password

