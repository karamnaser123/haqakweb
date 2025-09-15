# Discount API Documentation

## Overview
This document describes the discount API endpoints for the Haqak application. These endpoints allow users to apply, remove, and view available discounts for their orders.

## Authentication
All endpoints require authentication using Sanctum token. Include the token in the Authorization header:
```
Authorization: Bearer {your-token}
```

## Endpoints

### 1. Apply Discount Code
**POST** `/api/apply-discount`

Apply a discount code to the current pending order.

#### Request Body
```json
{
    "discount_code": "SAVE20"
}
```

#### Response
**Success (200)**
```json
{
    "success": true,
    "message": "Discount applied successfully",
    "discount": {
        "code": "SAVE20",
        "type": "percentage",
        "value": 20,
        "discount_amount": 15.00
    },
    "order": {
        "total_price": 75.00,
        "discount_amount": 15.00,
        "final_price": 60.00
    }
}
```

**Error (400)**
```json
{
    "success": false,
    "message": "Invalid discount code"
}
```

### 2. Remove Discount
**POST** `/api/remove-discount`

Remove the currently applied discount from the order.

#### Request Body
No body required.

#### Response
**Success (200)**
```json
{
    "success": true,
    "message": "Discount removed successfully",
    "order": {
        "total_price": 75.00,
        "discount_amount": 0,
        "final_price": 75.00
    }
}
```

### 3. Get Available Discounts
**GET** `/api/available-discounts`

Get all available discounts for the current order based on products and categories.

#### Response
**Success (200)**
```json
{
    "success": true,
    "discounts": [
        {
            "id": 1,
            "code": "SAVE20",
            "type": "percentage",
            "value": 20,
            "scope_type": "product",
            "scope_names": "Product A, Product B",
            "discount_amount": 15.00,
            "start_date": "2025-01-01",
            "end_date": "2025-12-31",
            "max_uses": 100,
            "uses": 25
        }
    ]
}
```

### 4. Get Cart (Updated)
**GET** `/api/cart`

Get the user's cart with discount information included.

#### Response
**Success (200)**
```json
{
    "cart": [
        {
            "id": 1,
            "user_id": 1,
            "store_id": 2,
            "total_price": 75.00,
            "discount_amount": 15.00,
            "final_price": 60.00,
            "discount_info": {
                "code": "SAVE20",
                "type": "percentage",
                "value": 20,
                "amount": 15.00
            },
            "order_items": [...],
            "store": {...},
            "discount": {...}
        }
    ]
}
```

## Discount Types

### Percentage Discount
- `type`: "percentage"
- `value`: Percentage value (e.g., 20 for 20%)
- Calculation: `discount_amount = order_total * (value / 100)`

### Fixed Amount Discount
- `type`: "fixed"
- `value`: Fixed amount (e.g., 10.00 for $10 off)
- Calculation: `discount_amount = min(value, order_total)`

## Discount Scopes

### General (All Products)
- Applies to all products in the order
- `scope_type`: "general"

### Specific Products
- Applies only to selected products
- `scope_type`: "product"
- Products must be in the order for discount to apply

### Product Categories
- Applies to products in selected categories
- `scope_type`: "category"
- At least one product in the order must belong to the selected category

## Validation Rules

1. **Active Status**: Discount must be active
2. **Date Validity**: Current date must be within start_date and end_date range
3. **Usage Limits**: Must not exceed max_uses limit
4. **Per-User Limits**: User must not exceed max_uses_per_user limit
5. **Scope Validity**: Products/categories in order must match discount scope

## Error Messages

- "Invalid discount code" - Discount code not found or inactive
- "Discount is not yet active" - Start date hasn't been reached
- "Discount has expired" - End date has passed
- "Discount usage limit reached" - Max uses exceeded
- "You have reached the maximum usage limit for this discount" - Per-user limit exceeded
- "This discount is not applicable to products in your order" - Scope mismatch
- "No pending order found" - No cart items to apply discount to
- "No discount applied to this order" - Trying to remove non-existent discount

## Usage Examples

### Apply a 20% discount
```bash
curl -X POST /api/apply-discount \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"discount_code": "SAVE20"}'
```

### Remove current discount
```bash
curl -X POST /api/remove-discount \
  -H "Authorization: Bearer {token}"
```

### Get available discounts
```bash
curl -X GET /api/available-discounts \
  -H "Authorization: Bearer {token}"
```

### Get cart with discount info
```bash
curl -X GET /api/cart \
  -H "Authorization: Bearer {token}"
```
