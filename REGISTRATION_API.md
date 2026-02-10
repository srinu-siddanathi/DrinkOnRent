# Registration API Documentation

This documentation details the API endpoints for user registration and authentication flow.

## 1. Send OTP

**Endpoint:** `POST /api/send-otp`

**Description:**
Sends an OTP to the provided phone number.

**Request Body:**
```json
{
    "phone": "1234567890"
}
```

**Response (Success - 200 OK):**
```json
{
    "message": "OTP sent successfully",
    "otp": 123456 // For development only
}
```

---

## 2. Verify OTP

**Endpoint:** `POST /api/verify-otp`

**Description:**
Verifies the OTP and logs the user in. If the user does not exist, a new account is created.

**Request Body:**
```json
{
    "phone": "1234567890",
    "otp": "123456"
}
```

**Response (Success - 200 OK):**
```json
{
    "message": "OTP verified successfully",
    "token": "auth_token_string",
    "customer": {
        "id": 1,
        "phone": "1234567890",
        "is_phone_verified": true,
        "first_name": null,
        "last_name": null,
        "email": null,
        ...
    }
}
```

---

## 3. Register (Complete Profile)

**Endpoint:** `POST /api/register`

**Description:**
Updates the user's profile with personal details. This endpoint requires authentication.

**Headers:**
- `Authorization`: Bearer <token>

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "gender": "male"
}
```

**Response (Success - 200 OK):**
```json
{
    "message": "Profile updated successfully",
    "customer": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john.doe@example.com",
        "gender": "male",
        "name": "John Doe",
        ...
    }
}
```
