# DrinkOnRent - Codebase Index

**Last Updated**: March 9, 2026  
**Framework**: Laravel 11.31  
**Language**: PHP 8.2+

---

## Project Summary

**DrinkOnRent** is a Laravel REST API for managing water purifier rentals with subscription plans, payments, and service management. Built for an Android application with OTP-based authentication and Razorpay payment integration.

---

## Technology Stack

- **Backend**: Laravel 11.31, PHP 8.2+
- **Database**: MySQL/SQLite (migrations available)
- **API**: REST with Laravel Sanctum (OAuth2 tokens)
- **Payment**: Razorpay API v2.9
- **Frontend**: Vite + Tailwind CSS + Vue.js (minimal)
- **Testing**: PHPUnit 11+
- **Other**: Faker, Mockery, Pint (linting)

---

## Core Models & Database Schema

| Model | Purpose | Key Fields | Relations |
|-------|---------|-----------|-----------|
| **Customer** | User profiles with phone verification | phone, email, first_name, last_name, gender, address, area, id_proof, is_phone_verified | hasMany(Subscription, SupportRequest, Purifier) |
| **Plan** | Subscription plans (RO/Alkaline purifiers) | name, description, purifier_type, litres, price, duration_in_days, is_active | hasMany(Subscription) |
| **Subscription** | Active subscriptions | customer_id, plan_id, purifier_id, status, payment_status, start_date, end_date, litres_consumed, litres_remaining | belongsTo(Customer, Plan); hasOne(Payment) |
| **Payment** | Razorpay payment records | subscription_id, razorpay_order_id, razorpay_payment_id, razorpay_signature, amount, currency, status | belongsTo(Subscription) |
| **Purifier** | Water purifier devices | customer_id, serial_number, mac_address, model, purifier_code, type, installation_date, last_service_date, next_service_date, latitude, longitude, location_address, has_rtc_error | belongsTo(Customer) |
| **SupportRequest** | Customer support tickets | customer_id, subject, message, status, admin_notes | belongsTo(Customer) |
| **PurifierRequest** | Requests for new purifiers | customer_id, purifier_type, latitude, longitude, location_address, status, source_of_drinking_water, date_of_birth, type_of_residence, share_with, current_profession | belongsTo(Customer) |
| **Admin** | Admin users | name, email, password | - |

---

## API Routes & Endpoints

**Base URL**: `/api`

### Public Endpoints (No Auth Required)

- `GET /test` - API health check
- `POST /send-otp` - Send OTP to phone (10-digit validation)
- `POST /verify-otp` - Verify OTP & auto-create customer account
- **Returns**: Bearer token for authenticated requests

### Protected Endpoints (Auth Required via Bearer Token)

#### Authentication & Profile
- `POST /register` - Complete registration (first_name, last_name, email, gender)
- `PUT /profile` - Update customer profile
- `GET /dashboard` - Get customer dashboard data

#### Plans
- `GET /plans` - List all active plans
- `GET /plans/{plan}` - Get specific plan details

#### Subscriptions
- `POST /subscriptions` - Create new subscription
- `GET /subscriptions/active` - Get active subscriptions
- `POST /subscriptions/{subscription}/activate` - Activate subscription
- `PUT /subscriptions/{subscription}/consumption` - Update litres consumed/remaining

#### Payments (Razorpay)
- `POST /subscriptions/{subscription}/pay` - Create Razorpay order
- `POST /payment/verify` - Verify Razorpay payment signature & activate subscription

#### Support
- `POST /support-requests` - Create support ticket
- `GET /support-requests` - List customer's support tickets
- `GET /support-requests/{supportRequest}` - Get ticket details

#### Purifier Management
- `POST /purifiers` - Register new purifier device
- `POST /purifier-requests` - Request new purifier
- `POST /purifiers/{purifierId}/plan` - Set plan for purifier
- `POST /purifiers/{purifierId}/rtc-error` - Log RTC error
- `POST /purifiers/{purifierId}/clear-rtc-error` - Clear RTC error
- `GET /current-datetime` - Get server datetime

---

## Controllers

Location: `app/Http/Controllers/Api/`

| Controller | Methods | Responsibility |
|-----------|---------|-----------------|
| **AuthController** | sendOtp(), verifyOtp(), register() | OTP generation/verification, customer creation, registration |
| **CustomerController** | updateProfile(), dashboard() | Profile management, dashboard data |
| **PlanController** | index(), show() | List and retrieve subscription plans |
| **SubscriptionController** | store(), active(), activate(), updateConsumption() | Subscription CRUD and management |
| **PaymentController** | createOrder(), verifyPayment() | Razorpay integration and payment verification |
| **SupportRequestController** | store(), index(), show() | Support ticket management |
| **PurifierRequestController** | store() | New purifier request handling |

---

## Key Business Logic

### Authentication Flow
- OTP cached for 5 minutes (development: OTP returned in response)
- Customers auto-created on first OTP verification
- Laravel Sanctum tokens for API authentication

### Subscription Lifecycle
- Status: pending → active → expired/cancelled
- Auto-set start_date when activated
- Auto-calculate end_date based on plan duration
- Track litres_consumed & litres_remaining

### Payment Integration
- Razorpay for payment processing
- Order creation before payment
- Signature verification for payment confirmation
- Subscription activation on successful payment

### Purifier Management
- RTC error tracking for devices
- Coordinates (latitude/longitude) stored
- Service tracking (installation, last service, next service)
- Location-based address storage

---

## File Structure

```
/app
  /Http
    /Controllers
      /Api/              ← API Controllers (AuthController, CustomerController, etc.)
      /Admin             ← Admin Controllers
      PurifierController.php
    /Middleware          ← Custom middleware
    Kernel.php
  /Models                ← Database models/relationships
  /Providers             ← Service providers

/routes
  api.php               ← All API endpoints
  web.php
  console.php

/database
  /migrations           ← Schema definitions
  /factories            ← Model factories for testing
  /seeders              ← Database seeders

/config
  services.php          ← Razorpay API keys
  auth.php
  database.php
  mail.php
  sanctum.php

/resources
  /views                ← Blade templates
  /js, /css

/tests
  TestCase.php
  /Feature
  /Unit

Documentation:
  README.md             ← Laravel default
  API_DOCUMENTATION.md  ← Full API reference (1050 lines)
  REGISTRATION_API.md   ← Auth flow details
  PAYMENT_API.md        ← Payment flow details
  PROJECT_INDEX.md      ← This file
  
Postman Collection:
  DrinkOnRent.postman_collection.json
```

---

## Configuration Files

- `composer.json` - PHP dependencies (Laravel 11.31, Razorpay 2.9, Sanctum 4.0)
- `package.json` - Node/Frontend dependencies (Vite, Tailwind, Axios)
- `vite.config.js` - Vite bundler config
- `tailwind.config.js` - Tailwind CSS config
- `postcss.config.js` - PostCSS config
- `Dockerfile` - Docker container setup
- `phpunit.xml` - PHPUnit testing config

---

## Key Dependencies

### PHP (composer.json)
- `laravel/framework: ^11.31`
- `laravel/sanctum: ^4.0` (API tokens)
- `razorpay/razorpay: ^2.9` (Payment processing)
- `laravel/tinker: ^2.9` (REPL)

### Node (package.json)
- `vite: ^6.0.11`
- `tailwindcss: ^3.4.13`
- `laravel-vite-plugin: ^1.2.0`
- `axios: ^1.7.4`

---

## Development Commands

```bash
# Start PHP server
php artisan serve

# Database
php artisan migrate
php artisan db:seed
php artisan tinker

# Clear cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Build frontend
npm run dev        # Development
npm run build      # Production

# Testing
php artisan test
./vendor/bin/phpunit
```

---

## Database Schema Quick Reference

### customers table
- id, name, phone (unique), email (unique), address, is_phone_verified, timestamps
- Soft deletes enabled
- Additional fields: first_name, last_name, gender, area, next_service_reminder, id_proof

### subscriptions table
- id, customer_id, plan_id, purifier_id, payment_status, start_date, end_date, status, litres_consumed, litres_remaining, timestamps

### plans table
- id, name, description, purifier_type (ro/alkaline), litres, price, duration_in_days, is_active, timestamps

### purifiers table
- id, customer_id, serial_number, mac_address, model, purifier_code, type, installation_date, last_service_date, next_service_date, latitude, longitude, location_address, has_rtc_error, rtc_error_updated_at

### payments table
- id, subscription_id, razorpay_order_id, razorpay_payment_id, razorpay_signature, amount, currency, status, timestamps

### support_requests table
- id, customer_id, subject, message, status, admin_notes, timestamps

### purifier_requests table
- id, customer_id, purifier_type, latitude, longitude, location_address, status, source_of_drinking_water, date_of_birth, type_of_residence, share_with, current_profession, timestamps

---

## Common Development Tasks

### Adding a New API Endpoint
1. Create controller method in `app/Http/Controllers/Api/[Name]Controller.php`
2. Add route in `routes/api.php`
3. Create model if needed in `app/Models/`
4. Create migration if DB changes: `php artisan make:migration`

### Payment Processing Flow
1. Controller: `PaymentController::createOrder()` - Creates Razorpay order
2. Frontend calls Razorpay payment dialog
3. Controller: `PaymentController::verifyPayment()` - Verifies signature
4. Updates `subscriptions.payment_status` and activates subscription

### Authentication Flow
1. `POST /api/send-otp` → Generate & cache OTP
2. `POST /api/verify-otp` → Verify & create/find customer
3. Returns Sanctum token for subsequent requests
4. `POST /api/register` → Complete profile with token

---

## Important Notes

- **OTP in Development**: Currently returns OTP in response for testing (remove in production)
- **Timezone**: Set to Asia/Kolkata for Purifier timestamps
- **Payment**: Uses Razorpay API (credentials in `config/services.php`)
- **Database**: Soft deletes enabled for Customers
- **Relationships**: Cascading deletes set up for foreign keys

---

## Related Documentation

See these files for detailed information:
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Complete API endpoints (1050 lines)
- [REGISTRATION_API.md](REGISTRATION_API.md) - Authentication flow
- [PAYMENT_API.md](PAYMENT_API.md) - Payment processing details
- [README.md](README.md) - Laravel framework documentation
