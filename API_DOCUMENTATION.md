# DrinkOnRent API Documentation

## Base URL
```
/api
```

## Authentication
Most endpoints require authentication using Laravel Sanctum. Include the bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

The token is obtained after successful OTP verification.

---

## Public Endpoints

### 1. Test Endpoint
Test if the API is working.

**Endpoint:** `GET /api/test`

**Authentication:** Not required

**Response:**
```json
{
  "status": "success",
  "data": {
    "plans": [...],
    "subscriptions": [...]
  },
  "message": "Test API is working!"
}
```

---

### 2. Send OTP
Send OTP to customer's phone number for authentication.

**Endpoint:** `POST /api/send-otp`

**Authentication:** Not required

**Request Body:**
```json
{
  "phone": "9876543210"
}
```

**Validation Rules:**
- `phone`: required, string, exactly 10 characters

**Response:**
```json
{
  "message": "OTP sent successfully",
  "otp": "123456"
}
```

**Note:** In production, the `otp` field should be removed from the response. Currently, it's included for development purposes.

---

### 3. Verify OTP
Verify OTP and authenticate customer. Returns authentication token.

**Endpoint:** `POST /api/verify-otp`

**Authentication:** Not required

**Request Body:**
```json
{
  "phone": "9876543210",
  "otp": "123456"
}
```

**Validation Rules:**
- `phone`: required, string, exactly 10 characters
- `otp`: required, string, exactly 6 characters

**Response:**
```json
{
  "message": "OTP verified successfully",
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "customer": {
    "id": 1,
    "name": "User_3210",
    "phone": "9876543210",
    "email": null,
    "address": null,
    "is_phone_verified": true,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

**Error Response (Invalid/Expired OTP):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "otp": ["The OTP is invalid or expired."]
  }
}
```

---

## Protected Endpoints (Require Authentication)

### Customer Profile

#### 4. Update Profile
Update customer profile information.

**Endpoint:** `PUT /api/profile`

**Authentication:** Required

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "address": "123 Main Street, City"
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `email`: nullable, valid email format, unique (except for current user)
- `address`: nullable, string

**Response:**
```json
{
  "message": "Profile updated successfully",
  "customer": {
    "id": 1,
    "name": "John Doe",
    "phone": "9876543210",
    "email": "john@example.com",
    "address": "123 Main Street, City",
    "is_phone_verified": true,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

#### 5. Get Dashboard
Get customer dashboard with active subscription, purifiers, and remaining days/litres.

**Endpoint:** `GET /api/dashboard`

**Authentication:** Required

**Response:**
```json
{
  "customer": {
    "id": 1,
    "name": "John Doe",
    "phone": "9876543210",
    "email": "john@example.com",
    "address": "123 Main Street, City",
    "is_phone_verified": true
  },
  "subscription": {
    "id": 1,
    "customer_id": 1,
    "plan_id": 2,
    "purifier_id": 1,
    "payment_status": "completed",
    "start_date": "2024-01-01T00:00:00.000000Z",
    "end_date": "2024-02-01T00:00:00.000000Z",
    "status": "active",
    "litres_consumed": 50,
    "litres_remaining": 450,
    "plan": {
      "id": 2,
      "name": "Standard Plan",
      "description": "Standard water purifier plan",
      "purifier_type": "ro",
      "litres": 500,
      "price": "199.99",
      "duration_in_days": 30,
      "is_active": true
    }
  },
  "purifiers": [
    {
      "id": 1,
      "customer_id": 1,
      "serial_number": "00001",
      "model": "Model X",
      "type": "ro",
      "installation_date": "2024-01-01T00:00:00.000000Z",
      "latitude": "12.9716",
      "longitude": "77.5946",
      "location_address": "123 Main Street",
      "has_rtc_error": false,
      "rtc_error_updated_at": null
    }
  ],
  "days_remaining": 15,
  "litres_remaining": 450
}
```

---

### Plans

#### 6. Get All Plans
Retrieve all available subscription plans.

**Endpoint:** `GET /api/plans`

**Authentication:** Required

**Response:**
```json
{
  "plans": [
    {
      "id": 1,
      "name": "Basic Plan",
      "description": "Basic water purifier plan",
      "purifier_type": "ro",
      "litres": 300,
      "price": "99.99",
      "duration_in_days": 30,
      "is_active": true,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Standard Plan",
      "description": "Standard water purifier plan",
      "purifier_type": "ro",
      "litres": 500,
      "price": "199.99",
      "duration_in_days": 30,
      "is_active": true,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

#### 7. Get Plan Details
Get details of a specific plan.

**Endpoint:** `GET /api/plans/{plan}`

**Authentication:** Required

**URL Parameters:**
- `plan`: Plan ID (integer)

**Response:**
```json
{
  "plan": {
    "id": 1,
    "name": "Basic Plan",
    "description": "Basic water purifier plan",
    "purifier_type": "ro",
    "litres": 300,
    "price": "99.99",
    "duration_in_days": 30,
    "is_active": true,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

### Subscriptions

#### 8. Create Subscription
Create a new subscription for a plan.

**Endpoint:** `POST /api/subscriptions`

**Authentication:** Required

**Request Body:**
```json
{
  "plan_id": 1
}
```

**Validation Rules:**
- `plan_id`: required, must exist in plans table

**Response:**
```json
{
  "message": "Subscription created successfully",
  "subscription": {
    "id": 1,
    "customer_id": 1,
    "plan_id": 1,
    "purifier_id": null,
    "payment_status": null,
    "start_date": null,
    "end_date": null,
    "status": "pending",
    "litres_consumed": 0,
    "litres_remaining": 300,
    "plan": {
      "id": 1,
      "name": "Basic Plan",
      "description": "Basic water purifier plan",
      "purifier_type": "ro",
      "litres": 300,
      "price": "99.99",
      "duration_in_days": 30,
      "is_active": true
    }
  }
}
```

---

#### 9. Get Active Subscription
Get the currently active subscription for the authenticated customer.

**Endpoint:** `GET /api/subscriptions/active`

**Authentication:** Required

**Response:**
```json
{
  "subscription": {
    "id": 1,
    "customer_id": 1,
    "plan_id": 2,
    "purifier_id": 1,
    "payment_status": "completed",
    "start_date": "2024-01-01T00:00:00.000000Z",
    "end_date": "2024-02-01T00:00:00.000000Z",
    "status": "active",
    "litres_consumed": 50,
    "litres_remaining": 450,
    "plan": {
      "id": 2,
      "name": "Standard Plan",
      "description": "Standard water purifier plan",
      "purifier_type": "ro",
      "litres": 500,
      "price": "199.99",
      "duration_in_days": 30,
      "is_active": true
    }
  },
  "days_remaining": 15,
  "litres_remaining": 450
}
```

**Error Response (No Active Subscription):**
```json
{
  "message": "No active subscription found"
}
```
Status Code: 404

---

#### 10. Activate Subscription
Activate a subscription after payment is completed.

**Endpoint:** `POST /api/subscriptions/{subscription}/activate`

**Authentication:** Required

**URL Parameters:**
- `subscription`: Subscription ID (integer)

**Response (Success):**
```json
{
  "message": "Subscription activated successfully",
  "subscription": {
    "id": 1,
    "customer_id": 1,
    "plan_id": 1,
    "status": "active",
    "start_date": "2024-01-01T00:00:00.000000Z",
    "end_date": "2024-02-01T00:00:00.000000Z",
    "plan": {...}
  }
}
```

**Error Response (Payment Not Completed):**
```json
{
  "message": "Payment not completed"
}
```
Status Code: 400

---

#### 11. Update Consumption
Update consumption details for a subscription.

**Endpoint:** `PUT /api/subscriptions/{subscription}/consumption`

**Authentication:** Required

**URL Parameters:**
- `subscription`: Subscription ID (integer)

**Request Body:**
```json
{
  "litres_consumed": 50,
  "litres_remaining": 450
}
```

**Validation Rules:**
- `litres_consumed`: required, integer, minimum 0
- `litres_remaining`: required, integer, minimum 0

**Response:**
```json
{
  "message": "Consumption details updated successfully",
  "subscription": {
    "id": 1,
    "customer_id": 1,
    "plan_id": 1,
    "litres_consumed": 50,
    "litres_remaining": 450,
    ...
  }
}
```

---

### Payments

#### 12. Process Payment
Process payment for a subscription.

**Endpoint:** `POST /api/subscriptions/{subscription}/pay`

**Authentication:** Required

**URL Parameters:**
- `subscription`: Subscription ID (integer)

**Request Body:**
```json
{
  "payment_method": "payment_method_token_or_id"
}
```

**Validation Rules:**
- `payment_method`: required, string

**Response (Success):**
```json
{
  "message": "Payment successful and subscription activated",
  "subscription": {
    "id": 1,
    "customer_id": 1,
    "plan_id": 1,
    "payment_status": "completed",
    "status": "active",
    "start_date": "2024-01-01T00:00:00.000000Z",
    "end_date": "2024-02-01T00:00:00.000000Z",
    ...
  }
}
```

**Error Response (Payment Failed):**
```json
{
  "message": "Payment failed"
}
```
Status Code: 400

**Note:** Currently, this endpoint simulates payment processing. Integration with actual payment gateway (e.g., Stripe, Razorpay) is required for production.

---

### Support Requests

#### 13. Create Support Request
Create a new support request.

**Endpoint:** `POST /api/support-requests`

**Authentication:** Required

**Request Body:**
```json
{
  "subject": "Water quality issue",
  "message": "The water from the purifier has a strange taste."
}
```

**Validation Rules:**
- `subject`: required, string, max 255 characters
- `message`: required, string

**Response:**
```json
{
  "message": "Support request created successfully",
  "support_request": {
    "id": 1,
    "customer_id": 1,
    "subject": "Water quality issue",
    "message": "The water from the purifier has a strange taste.",
    "status": "open",
    "admin_notes": null,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```
Status Code: 201

---

#### 14. Get All Support Requests
Get all support requests for the authenticated customer.

**Endpoint:** `GET /api/support-requests`

**Authentication:** Required

**Response:**
```json
{
  "support_requests": [
    {
      "id": 1,
      "customer_id": 1,
      "subject": "Water quality issue",
      "message": "The water from the purifier has a strange taste.",
      "status": "open",
      "admin_notes": null,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    {
      "id": 2,
      "customer_id": 1,
      "subject": "Service request",
      "message": "Need maintenance for my purifier.",
      "status": "resolved",
      "admin_notes": "Service completed on 2024-01-05",
      "created_at": "2024-01-03T00:00:00.000000Z",
      "updated_at": "2024-01-05T00:00:00.000000Z"
    }
  ]
}
```

---

#### 15. Get Support Request Details
Get details of a specific support request.

**Endpoint:** `GET /api/support-requests/{supportRequest}`

**Authentication:** Required

**URL Parameters:**
- `supportRequest`: Support Request ID (integer)

**Response:**
```json
{
  "support_request": {
    "id": 1,
    "customer_id": 1,
    "subject": "Water quality issue",
    "message": "The water from the purifier has a strange taste.",
    "status": "open",
    "admin_notes": null,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

**Error Response (Unauthorized):**
```json
{
  "message": "Unauthorized access"
}
```
Status Code: 403

**Note:** Customers can only view their own support requests.

---

### Purifier Management

#### 16. Create Purifier
Create a new purifier for the authenticated customer.

**Endpoint:** `POST /api/purifiers`

**Authentication:** Required

**Request Body:**
```json
{
  "model": "Model X",
  "type": "ro",
  "installation_date": "2024-01-01T00:00:00Z",
  "latitude": 12.9716,
  "longitude": 77.5946,
  "location_address": "123 Main Street, City"
}
```

**Validation Rules:**
- `model`: required, string
- `type`: required, string
- `installation_date`: nullable, valid date
- `latitude`: nullable, numeric
- `longitude`: nullable, numeric
- `location_address`: nullable, string

**Response:**
```json
{
  "message": "Purifier created successfully",
  "purifier": {
    "id": 1,
    "customer_id": 1,
    "serial_number": "00001",
    "model": "Model X",
    "type": "ro",
    "installation_date": "2024-01-01 00:00:00",
    "last_service_date": null,
    "next_service_date": null,
    "latitude": "12.97160000",
    "longitude": "77.59460000",
    "location_address": "123 Main Street, City",
    "has_rtc_error": false,
    "rtc_error_updated_at": null,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```
Status Code: 201

**Note:** 
- `customer_id` is automatically set from the authenticated user
- `serial_number` is auto-generated if not provided
- Dates are stored in Asia/Kolkata timezone

---

#### 17. Set Plan for Purifier
Set a subscription plan for a specific purifier.

**Endpoint:** `POST /api/purifiers/{purifierId}/plan`

**Authentication:** Required

**URL Parameters:**
- `purifierId`: Purifier ID (integer)

**Request Body:**
```json
{
  "plan_id": 1,
  "start_date": "2024-01-01T00:00:00Z"
}
```

**Validation Rules:**
- `plan_id`: required, must exist in plans table
- `start_date`: required, valid date

**Response:**
```json
{
  "message": "Plan set successfully",
  "subscription": {
    "id": 1,
    "customer_id": 1,
    "plan_id": 1,
    "purifier_id": 1,
    "start_date": "2024-01-01 00:00:00",
    "end_date": "2024-02-01 00:00:00",
    "status": "active",
    "litres_remaining": 300,
    ...
  }
}
```

**Note:** 
- The purifier must belong to the authenticated customer
- `end_date` is automatically calculated based on plan's `duration_in_days`
- Dates are stored in Asia/Kolkata timezone

---

#### 18. Set RTC Error Status
Set RTC (Real-Time Clock) error status for a purifier.

**Endpoint:** `POST /api/purifiers/{purifierId}/rtc-error`

**Authentication:** Required

**URL Parameters:**
- `purifierId`: Purifier ID (integer)

**Request Body:**
```json
{
  "has_rtc_error": true
}
```

**Validation Rules:**
- `has_rtc_error`: required, boolean

**Response:**
```json
{
  "message": "RTC error status updated successfully",
  "purifier": {
    "id": 1,
    "customer_id": 1,
    "serial_number": "00001",
    "model": "Model X",
    "type": "ro",
    "has_rtc_error": true,
    "rtc_error_updated_at": "2024-01-01 12:00:00",
    ...
  }
}
```

**Note:** The purifier must belong to the authenticated customer.

---

#### 19. Clear RTC Error
Clear RTC error status for a purifier.

**Endpoint:** `POST /api/purifiers/{purifierId}/clear-rtc-error`

**Authentication:** Required

**URL Parameters:**
- `purifierId`: Purifier ID (integer)

**Response:**
```json
{
  "message": "RTC error cleared successfully",
  "purifier": {
    "id": 1,
    "customer_id": 1,
    "serial_number": "00001",
    "model": "Model X",
    "type": "ro",
    "has_rtc_error": false,
    "rtc_error_updated_at": "2024-01-01 12:00:00",
    ...
  }
}
```

**Note:** The purifier must belong to the authenticated customer.

---

#### 20. Get Current DateTime
Get the current date and time in Asia/Kolkata timezone.

**Endpoint:** `GET /api/current-datetime`

**Authentication:** Required

**Response:**
```json
{
  "current_datetime": "2024-01-01T12:00:00+05:30",
  "timezone": "Asia/Kolkata (UTC+5:30)"
}
```

---

## Data Models

### Customer
```json
{
  "id": 1,
  "name": "John Doe",
  "phone": "9876543210",
  "email": "john@example.com",
  "address": "123 Main Street, City",
  "is_phone_verified": true,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Plan
```json
{
  "id": 1,
  "name": "Basic Plan",
  "description": "Basic water purifier plan",
  "purifier_type": "ro",
  "litres": 300,
  "price": "99.99",
  "duration_in_days": 30,
  "is_active": true,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Subscription
```json
{
  "id": 1,
  "customer_id": 1,
  "plan_id": 1,
  "purifier_id": 1,
  "payment_status": "completed",
  "start_date": "2024-01-01T00:00:00.000000Z",
  "end_date": "2024-02-01T00:00:00.000000Z",
  "status": "active",
  "litres_consumed": 50,
  "litres_remaining": 450,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Purifier
```json
{
  "id": 1,
  "customer_id": 1,
  "serial_number": "00001",
  "model": "Model X",
  "type": "ro",
  "installation_date": "2024-01-01 00:00:00",
  "last_service_date": null,
  "next_service_date": null,
  "latitude": "12.97160000",
  "longitude": "77.59460000",
  "location_address": "123 Main Street, City",
  "has_rtc_error": false,
  "rtc_error_updated_at": null,
  "created_at": "2024-01-01 00:00:00",
  "updated_at": "2024-01-01 00:00:00"
}
```

### SupportRequest
```json
{
  "id": 1,
  "customer_id": 1,
  "subject": "Water quality issue",
  "message": "The water from the purifier has a strange taste.",
  "status": "open",
  "admin_notes": null,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

---

## Error Responses

### Validation Errors (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "Unauthorized access"
}
```

### Not Found (404)
```json
{
  "message": "No active subscription found"
}
```

### Bad Request (400)
```json
{
  "message": "Payment not completed"
}
```

---

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## Notes

1. **Authentication:** All protected endpoints require a valid Sanctum token in the Authorization header.

2. **OTP:** Currently, OTP is returned in the response for development. This should be removed in production and integrated with an SMS gateway.

3. **Payment Processing:** The payment endpoint currently simulates payment processing. Integration with a payment gateway (e.g., Stripe, Razorpay) is required for production.

4. **Timezone:** All dates are stored and returned in Asia/Kolkata timezone (UTC+5:30).

5. **Serial Numbers:** Purifier serial numbers are auto-generated if not provided.

6. **Subscription Status:** Possible values are `pending`, `active`, `expired`, `cancelled`.

7. **Payment Status:** Possible values are `pending`, `completed`, `failed`.

8. **Support Request Status:** Possible values are `open`, `in_progress`, `resolved`, `closed`.

9. **Purifier Type:** Common values are `ro` (Reverse Osmosis) and `alkaline`.

---

## Example Usage

### Complete Authentication Flow

1. **Send OTP:**
```bash
curl -X POST http://your-domain.com/api/send-otp \
  -H "Content-Type: application/json" \
  -d '{"phone": "9876543210"}'
```

2. **Verify OTP:**
```bash
curl -X POST http://your-domain.com/api/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"phone": "9876543210", "otp": "123456"}'
```

3. **Use Token for Protected Endpoints:**
```bash
curl -X GET http://your-domain.com/api/dashboard \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" \
  -H "Content-Type: application/json"
```

---

## Additional Files

There is also a legacy `api.php` file in the root directory that provides sample data endpoints. This appears to be a standalone PHP file for testing purposes and is not part of the main Laravel API routes.






















