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

## PayPal Integration

This application includes PayPal integration for online payments. To set up PayPal:

1. Create a developer account at [PayPal Developer](https://developer.paypal.com/) if you don't already have one.

2. Create a PayPal app to get your API credentials:
   - Go to the [Developer Dashboard](https://developer.paypal.com/dashboard/) and log in
   - Click on "My Apps & Credentials"
   - Create a new app (make sure it's in Sandbox for testing)
   - Once created, copy the Client ID and Secret

3. Add the credentials to your `.env` file:
   ```
   PAYPAL_MODE=sandbox
   PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id_here
   PAYPAL_SANDBOX_CLIENT_SECRET=your_sandbox_secret_here
   ```
4. For testing, you can use PayPal Sandbox accounts:
   - Go to the Sandbox section in your PayPal developer dashboard
   - Create or use existing sandbox accounts
   - Use these accounts to test the payment flow

5. When you're ready to go live:
   - Create a live PayPal app in your developer dashboard
   - Update your `.env` file with live credentials:
     ```
     PAYPAL_MODE=live
     PAYPAL_LIVE_CLIENT_ID=your_live_client_id_here
     PAYPAL_LIVE_CLIENT_SECRET=your_live_secret_here
     ```

### Testing PayPal Payments

To test the PayPal integration:
1. Add products to your cart
2. Proceed to checkout
3. Select "PayPal" as the payment method
4. Click "Place Order"
5. You will be redirected to the PayPal sandbox environment
6. Log in with a PayPal sandbox account
7. Complete the payment
8. You will be redirected back to the application


The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
