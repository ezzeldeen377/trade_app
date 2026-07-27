# Trade Online (6Valley) - Project Summary

## Overview

**Trade Online** is a full-featured multi-vendor e-commerce platform built on **Laravel 12** with PHP 8.2+. The application name is **6Valley**. It supports multiple vendors/sellers, delivery men, customers, and a full admin panel. The frontend uses **Vue 2** with **Laravel Mix** (Webpack) and **Bootstrap 4**.

---

## Tech Stack

| Layer             | Technology                                      |
|-------------------|------------------------------------------------|
| Backend Framework | Laravel 12 (PHP 8.2+)                          |
| Frontend          | Vue 2, Bootstrap 4, jQuery, Laravel Mix        |
| Database          | MySQL (via Eloquent ORM + Doctrine DBAL)       |
| Authentication    | Laravel Passport + Sanctum (API tokens)         |
| Real-time         | Firebase Cloud Messaging (FCM)                  |
| PDF Generation    | DomPDF, mPDF                                    |
| Excel/CSV         | Maatwebsite Excel, PhpSpreadsheet, OpenSpout    |
| Image Processing  | Intervention Image, Spatie Image Optimizer      |
| Modules           | nwidart/laravel-modules                        |
| Payment Gateways  | Stripe, PayPal, Razorpay, MercadoPago, bKash, SSLCommerz, Flutterwave, Paystack, Paytabs, Paytm, PhonePe, LiqPay, SenangPay, Paymob, Xendit |
| AI Integration    | OpenAI (via `openai-php/laravel`)               |
| SMS               | Twilio, Nexmo                                   |
| Search/SEO        | Spatie Laravel Sitemap, DeepLinks               |

---

## Project Structure

```
trade-online/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Contracts/            # Interface contracts
│   ├── Enums/                # PHP enums
│   ├── Events/               # Event classes
│   ├── Exceptions/           # Exception handlers
│   ├── Exports/              # Export classes (Excel/CSV)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Admin panel controllers (36 sub-modules)
│   │   │   ├── Auth/         # Authentication controllers
│   │   │   ├── Customer/     # Customer-facing controllers
│   │   │   ├── Payment_Methods/  # 13 payment gateway controllers
│   │   │   ├── RestAPI/      # REST API controllers
│   │   │   ├── Vendor/       # Vendor/seller panel controllers (23 sub-modules)
│   │   │   └── Web/          # Web controllers
│   │   ├── Middleware/        # HTTP middleware
│   │   ├── Requests/         # Form request validation
│   │   └── Resources/        # API resources
│   ├── Jobs/                 # Queue jobs
│   ├── Library/              # Constants, responses
│   ├── Listeners/            # Event listeners
│   ├── Mail/                 # Mailable classes
│   ├── Models/               # Eloquent models (111+ models)
│   ├── Observers/            # Model observers
│   ├── Packages/             # Local packages
│   ├── Providers/            # Service providers
│   ├── Repositories/         # Repository pattern classes
│   ├── Rules/                # Custom validation rules
│   ├── Services/             # Service classes
│   ├── Traits/               # Reusable traits
│   └── Utils/                # Helper utilities (20+ files)
├── Modules/
│   ├── AI/                   # AI integration module (OpenAI providers, addons)
│   ├── Blog/                 # Blog module
│   └── TaxModule/            # Tax management module
├── config/                   # Configuration files (33 files)
├── database/
│   ├── migrations/           # 280+ migration files
│   └── seeds/                # Database seeders
├── resources/
│   ├── js/                   # JavaScript/Vue assets
│   ├── lang/                 # Language files
│   ├── themes/
│   │   ├── default/          # Default storefront theme
│   │   └── theme_aster/      # Alternative theme
│   └── views/
│       ├── admin-views/      # Admin panel Blade views
│       ├── email-templates/  # Email templates
│       ├── vendor-views/     # Vendor panel Blade views
│       └── installation/     # Installation wizard views
├── routes/
│   ├── admin/                # Admin panel routes
│   ├── web/                  # Customer-facing web routes
│   ├── rest_api/
│   │   ├── v1/               # REST API v1
│   │   ├── v2/               # REST API v2
│   │   └── v3/               # REST API v3
│   └── vendor/               # Vendor panel routes
├── public/                   # Public assets (compiled JS/CSS, images)
├── storage/                  # Logs, cache, uploads
├── tests/                    # PHPUnit tests
└── vendor/                   # Composer dependencies
```

---

## Key Features

### Multi-Vendor Marketplace
- Vendor registration, approval, and shop management
- Per-vendor product catalogs, coupons, and promotions
- Vendor wallets, earnings, and withdrawal system
- Vendor order management and POS system

### Customer Features
- Product browsing with categories, brands, tags, and search
- Shopping cart with shipping calculations
- Multiple payment gateway support
- Order tracking and history
- Wishlist, product comparison, loyalty points
- Digital product purchases with OTP verification
- Referral system

### Admin Panel
- Dashboard with analytics
- Product, category, brand, and attribute management
- Order management with status tracking and refunds
- Vendor and customer management
- Delivery man management
- Payment and transaction reports
- System settings (business, SMS, email, shipping)
- Flash deals, coupons, and promotions
- Email templates and notification management

### Delivery System
- Delivery man registration and management
- Delivery tracking and history
- Delivery verification (OTP)
- Wallet system for delivery men
- Zip code and country code-based delivery

### Additional Modules
- **AI Module**: OpenAI-powered features (AI providers, addons)
- **Blog Module**: Content management for blog posts
- **TaxModule**: Tax management and calculations

---

## Payment Gateways (13)

| Gateway       | Controller                              |
|---------------|-----------------------------------------|
| Stripe        | StripePaymentController                 |
| PayPal        | PaypalPaymentController                 |
| Razorpay      | RazorPayController                      |
| MercadoPago   | MercadoPagoController                   |
| bKash         | BkashPaymentController                  |
| SSLCommerz    | SslCommerzPaymentController             |
| Flutterwave   | FlutterwaveV3Controller                 |
| Paystack      | PaystackController                      |
| Paytabs       | PaytabsController                       |
| Paytm         | PaytmController                         |
| PhonePe       | (via SDK)                               |
| LiqPay        | LiqPayController                        |
| SenangPay     | SenangPayController                     |
| Paymob        | PaymobController                        |
| Xendit        | (via SDK)                               |

---

## API Versions

- **REST API v1** - `routes/rest_api/v1/api.php`
- **REST API v2** - `routes/rest_api/v2/`
- **REST API v3** - `routes/rest_api/v3/`

Authentication via **Laravel Passport** (OAuth2) and **Sanctum** (token-based).

---

## Database

- **MySQL** as primary database
- Default database name: `6valley`
- **280+ migration files** covering all features
- **111+ Eloquent models**
- Uses Doctrine DBAL for column modifications

---

## Themes

Two frontend themes available in `resources/themes/`:
- **default** - Default storefront theme
- **theme_aster** - Alternative storefront theme

---

## Autoloaded Utility Files

The project auto-loads 20+ utility/helper files via Composer:
- `app/Library/Constant.php`, `Responses.php`
- `app/Utils/` - BackEndHelper, BrandManager, CategoryManager, CartManager, Convert, CustomerManager, FileManagerLogic, Helpers, ImageManager, OrderManager, ProductManager, SMSModule, and more

---

## Configuration Files (33)

Key configs: `app.php`, `database.php`, `filesystems.php`, `passport.php`, `openai.php`, `paypal.php`, `razor.php`, `services.php`, `modules.php`, `system-addons.php`, and payment gateway-specific configs.

---

## How to Run Locally

### Prerequisites

- **PHP 8.2+** with extensions: curl, dom, fileinfo, gd, intl, json, libxml, mysqli, openssl, zip
- **Composer** (latest)
- **Node.js** (16+) and **npm**
- **MySQL 8.0+**
- **Git**

### Step-by-Step Setup

```bash
# 1. Clone the repository
git clone <repository-url>
cd trade-online

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Create .env from example
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Create a MySQL database named "6valley"
mysql -u root -e "CREATE DATABASE 6valley;"

# 7. Update .env with your database credentials
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=6valley
#    DB_USERNAME=root
#    DB_PASSWORD=your_password

# 8. Run database migrations
php artisan migrate

# 9. Seed the database (if seeders are available)
php artisan db:seed

# 10. Link storage for file uploads
php artisan storage:link

# 11. Compile frontend assets (development)
npm run dev

# 12. Start the Laravel development server
php artisan serve

# The app will be available at http://localhost:8000
```

### Alternative: Using Laravel Sail (Docker)

```bash
# Install sail dependencies
composer require laravel/sail --dev

# Start the Docker containers
./vendor/bin/sail up -d

# Run migrations inside the container
./vendor/bin/sail artisan migrate

# Compile assets
./vendor/bin/sail npm run dev
```

### Environment Variables to Configure

| Variable          | Description                          |
|-------------------|--------------------------------------|
| `APP_URL`         | Application URL (e.g., http://localhost:8000) |
| `DB_*`            | MySQL connection settings            |
| `OPENAI_API_KEY`  | OpenAI API key (for AI features)     |
| `PURCHASE_CODE`   | Envato purchase code                 |
| `BUYER_USERNAME`  | Envato buyer username                |
| `NEXMO_KEY/SECRET`| Nexmo SMS credentials                |
| `AWS_*`           | AWS S3 storage credentials           |

### Development Commands

```bash
# Watch for asset changes
npm run watch

# Build for production
npm run prod

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run tests
php artisan test

# Check routes
php artisan route:list
```

---

## Installation Wizard

The project includes an installation wizard at `resources/views/installation/`. Routes are managed via:
- `routes/install.php`
- `installation/activate_install_routes.txt`
- `installation/activate_update_routes.txt`

---

## Notes

- The project uses **nwidart/laravel-modules** for modular architecture
- **Firebase** is used for push notifications (FCM)
- **OpenAI** integration is available for AI-powered features
- The application supports **multi-language** via `resources/lang/`
- **Two database SQL files** exist in `database/migrations/`: `addon_settings.sql` and `payment_requests.sql`
