# Payment API Documentation

This documentation details the API endpoints for processing payments using Razorpay in the Android application.

## 1. Create Payment Order

**Endpoint:** `POST /api/subscriptions/{subscriptionId}/pay`

**Description:**
Initiates a payment order with Razorpay for a specific subscription. This should be called when the user clicks the "Pay" button.

**Headers:**
- `Authorization`: Bearer <token>
- `Accept`: `application/json`
- `Content-Type`: `application/json`

**URL Parameters:**
- `subscriptionId`: The ID of the subscription being paid for.

**Response (Success - 200 OK):**

```json
{
    "order_id": "order_EKwxwVidXV1Mbo",
    "amount": 50000,
    "currency": "INR",
    "key": "rzp_test_placeholder",
    "name": "Your App Name",
    "description": "Payment for Premium Plan",
    "prefill": {
        "name": "Customer Name",
        "email": "customer@example.com",
        "contact": "9876543210"
    },
    "theme": {
        "color": "#3399cc"
    }
}
```

**Usage in Android:**
Use the `order_id` and `key` from this response to launch the Razorpay Checkout form.

---

## 2. Verify Payment

**Endpoint:** `POST /api/payment/verify`

**Description:**
Verifies the payment signature returned by Razorpay after a successful transaction. This confirms the payment is valid and updates the subscription status.

**Headers:**
- `Authorization`: Bearer <token>
- `Accept`: `application/json`
- `Content-Type`: `application/json`

**Request Body:**

```json
{
    "razorpay_order_id": "order_EKwxwVidXV1Mbo",
    "razorpay_payment_id": "pay_29QQoPNiJQ9",
    "razorpay_signature": "e84e1b9b..."
}
```

**Response (Success - 200 OK):**

```json
{
    "message": "Payment successful and subscription activated",
    "subscription": {
        "id": 1,
        "status": "active",
        "payment_status": "completed",
        "start_date": "2024-01-01T00:00:00.000000Z",
        "end_date": "2024-01-31T00:00:00.000000Z",
        ...
    }
}
```

**Response (Error - 400 Bad Request):**

```json
{
    "message": "Payment verification failed",
    "error": "Reason for failure..."
}
```

**Usage in Android:**
Call this endpoint inside the `onPaymentSuccess` callback of the Razorpay SDK, passing the `payment_id` and `signature` received along with the `order_id` used.
