# WebEntwicklungsProjekt

## Overview
This repository is a full-featured web development project that includes both frontend and backend components.
It represents a webshop system with administrative functions such as managing products, customers, orders, and coupons.

## Project Structure
- **Frontend**: Contains HTML, CSS, and JavaScript files to build the user interface.
- **Backend**: Consists of PHP scripts for handling business logic, database communication, and user sessions.
- **SQL**: Includes a database schema for setting up MySQL tables.

## Features
- Admin Dashboard for managing:
  - Products
  - Customers
  - Orders (with download invoice feature)
  - Coupons
- User Registration/Login System
- Shopping Cart
- AJAX-based interaction (JSON)
- Secure session management
- PDF invoice generation with FPDF

## Installation

To get the project up and running locally, follow these steps:

### 1. Clone the repository:
```bash
git clone https://github.com/muesli01/WebEntwicklungsProjekt-
```

### 2. Set up the database
- Import `webshop.sql` into your MySQL server.

### 3. Configure the database connection
- Edit `/Backend/config/dbaccess.php` with your DB credentials.

### 4. Set up a local server (e.g., XAMPP/Laragon)
- Place the project folder in `htdocs` (or equivalent).
- Start Apache and MySQL.

### 5. Access the application
Open `http://localhost/WebEntwicklungsProjekt-/Frontend/sites/index.html` in your browser.

## Testzugang
```
Benutzername: 111
Passwort: 111111
```
## Admin Zugriff
Benutzername: admin
Passwort: admin1
## License
This project is for educational purposes only.
