# Student Management System

## Introduction

The Student Management System is a simple web application built using the Laravel framework and Filament as the admin dashboard. This project allows users to manage students, classes, and sections. It also includes the integration of PDF generation and QR code generation.

## Features

* **Student management**: Create, read, update, and delete student records.

* **Class management**: Manage classes, including creating, reading, updating, and deleting classes.

* **Section management**: Manage sections within each class, including creating, reading, updating, and deleting sections.

* **PDF generation**: Generate PDF reports for student information.

* **QR code generation**: Generate QR codes for student details.

## Installation

1. **Clone the repository**:
      ```
      git clone https://github.com/yaman-shahbander-dev/student-management-system.git
      ```

2. **Navigate to the project directory**:
      ```
      cd student-management-system
      ```

3. **Install dependencies**:
      ```
      composer install
      ```

4. **Create a new .env file and configure the environment variables**:
      ```
      cp .env.example .env
      ```
      Open the .env file and update the following settings:
      
      * **DB_CONNECTION**: Set the database connection type (e.g., mysql, postgresql, sqlite).
      * **DB_HOST**, **DB_PORT**, **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD**: Set the database connection details.

5. **Generate an application key**:
      ```
      php artisan key:generate
      ```

6. **Run the database migrations**:
      ```
      php artisan migrate
      ```

7. **Seed the database (optional)**:
      ```
      php artisan db:seed
      ```

8. **Create a Filament User**:
      ```
      php artisan make:filament-user
      ```

9. **Start the development server**:
      ```
      php artisan serve
      ```

10. **Access the Filament admin dashboard**:

      Open your web browser and navigate to http://localhost/admin/login
    
      Use the Filament admin credentials you have provided in step eight

## Packages Used

The following packages have been used in this project:

* [Filament](https://filamentphp.com/)
* [Laravel Invoices](https://github.com/LaravelDaily/laravel-invoices)
* [Laravel Excel](https://laravel-excel.com/)
* [Simple QR Code](https://github.com/SimpleSoftwareIO/simple-qrcode)
