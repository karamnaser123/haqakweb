# Discount API Test Examples

## Test Scenarios

### 1. Create Test Data
First, create some test discounts in the admin panel:

#### General Discount (20% off all products)
- Code: `SAVE20`
- Type: Percentage
- Value: 20
- Scope: General (All Products)
- Active: Yes
- Max Uses: 100

#### Product-Specific Discount (10% off specific products)
- Code: `PRODUCT10`
- Type: Percentage
- Value: 10
- Scope: Specific Product
- Products: Select some products
- Active: Yes
- Max Uses: 50

#### Category Discount ($5 off electronics)
- Code: `ELECTRONICS5`
- Type: Fixed Amount
- Value: 5.00
- Scope: Product Category
- Categories: Select electronics category
- Active: Yes
- Max Uses: 200

### 2. Test API Endpoints

#### Step 1: Add products to cart
```bash
# Add product 1 to cart
curl -X POST http://localhost:8000/api/addtocart \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "quantity": 2}'

# Add product 2 to cart
curl -X POST http://localhost:8000/api/addtocart \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -d '{"product_id": 2, "quantity": 1}'
```

#### Step 2: Check cart
```bash
curl -X GET http://localhost:8000/api/cart \
  -H "Authorization: Bearer {your-token}"
```

#### Step 3: Get available discounts
```bash
curl -X GET http://localhost:8000/api/available-discounts \
  -H "Authorization: Bearer {your-token}"
```

#### Step 4: Apply discount
```bash
curl -X POST http://localhost:8000/api/apply-discount \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -d '{"discount_code": "SAVE20"}'
```

#### Step 5: Check cart with discount
```bash
curl -X GET http://localhost:8000/api/cart \
  -H "Authorization: Bearer {your-token}"
```

#### Step 6: Remove discount
```bash
curl -X POST http://localhost:8000/api/remove-discount \
  -H "Authorization: Bearer {your-token}"
```

### 3. Expected Results

#### Cart Response (with discount applied)
```json
{
    "cart": [
        {
            "id": 1,
            "user_id": 1,
            "store_id": 2,
            "total_price": 100.00,
            "discount_amount": 20.00,
            "final_price": 80.00,
            "discount_info": {
                "code": "SAVE20",
                "type": "percentage",
                "value": 20,
                "amount": 20.00
            },
            "order_items": [
                {
                    "id": 1,
                    "product_id": 1,
                    "quantity": 2,
                    "price": 30.00,
                    "total_price": 60.00
                },
                {
                    "id": 2,
                    "product_id": 2,
                    "quantity": 1,
                    "price": 40.00,
                    "total_price": 40.00
                }
            ]
        }
    ]
}
```

### 4. Error Testing

#### Test invalid discount code
```bash
curl -X POST http://localhost:8000/api/apply-discount \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -d '{"discount_code": "INVALID"}'
```

Expected response:
```json
{
    "success": false,
    "message": "Invalid discount code"
}
```

#### Test removing discount when none applied
```bash
curl -X POST http://localhost:8000/api/remove-discount \
  -H "Authorization: Bearer {your-token}"
```

Expected response:
```json
{
    "success": false,
    "message": "No discount applied to this order"
}
```

### 5. Mobile App Integration

#### JavaScript/Fetch Example
```javascript
// Apply discount
async function applyDiscount(discountCode) {
    try {
        const response = await fetch('/api/apply-discount', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                discount_code: discountCode
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Discount applied:', data.discount);
            console.log('Final price:', data.order.final_price);
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Network error:', error);
    }
}

// Get available discounts
async function getAvailableDiscounts() {
    try {
        const response = await fetch('/api/available-discounts', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Available discounts:', data.discounts);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
```

### 6. Admin Panel Testing

1. Go to Admin Panel → Discounts
2. Create test discounts with different scopes
3. Test the discount management interface
4. Verify discount usage tracking
5. Test discount validation rules
