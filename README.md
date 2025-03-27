# E-commerce System
Simple e-commerce system build with Laravel 10, featuring authentication, product management, cart functionality, checkout process, and order management.

## Features

### User Authentication
- Login and  Registration
- User Profile Management

### Admin Features
- Product management (CRUD Operation)
- Customer management
- Order management

### Customer Features
- Browse products
- Add Products to cart
- Manage cart (update quantities, remove items)
- Check process
- View order history

## Installation

1. Clone the repository:
```bash
git clone 
cd laravel-ecommerce
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Create a copy of the .env file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in the .env file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations and seed the database:
```bash
php artisan migrate --seed
```

7. Create a symbolic link for storage:
```bash
php artisan storage:link
```

8. Compile assets:
```bash
npm run dev
```

9. Start the development server:
```bash
php artisan serve
```

## Default Users

After seeding the database, you can use the following credentials to log in:

### Admin
- Email: admin@example.com
- Password: password

### Customers
- Email: john@example.com
- Password: password

- Email: jane@example.com
- Password: password

## Usage

### Admin Panel

From the admin panel, you can:
- Manage products (add, edit, delete)
- Manage customers (view, edit, delete)
- Manage orders (view, update status, delete)

### Customer Features
After logging in as a customer, you can:
- Browse products on the `/products` page
- View Product Details
- Add Products to cart
- View and manage your cart
- Complete the checkout process
- View your order history

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
