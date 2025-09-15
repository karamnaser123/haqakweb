<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Governorate;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function cart(Request $request)
    {
        $user = $request->user();
        $cart = Order::where('user_id', $user->id)
            ->with('orderItems.product.productImages', 'store', 'discount')
            ->where('status', 'pending')
            ->get();

        // Add discount info to each order
        $cart->each(function ($order) {
            $order->discount_info = $order->discount ? [
                'code' => $order->discount->code,
                'type' => $order->discount->type,
                'value' => $order->discount->value,
                'amount' => $order->discount_amount,
            ] : null;
        });

        return response()->json([
            'cart' => $cart,
        ]);
    }
    public function addtocart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $product = Product::with('store')->find($request->product_id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $user = $request->user();

            $store = User::find($product->store_id);

            DB::transaction(function () use ($request, $product, $user, $store) {
                // البحث عن طلبات موجودة للمستخدم
                $existingOrders = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->get();

                if ($existingOrders->isNotEmpty()) {
                    // فحص إذا كان هناك طلب من نفس المتجر
                    $sameStoreOrder = $existingOrders->where('store_id', $product->store_id)->first();

                    if ($sameStoreOrder) {
                        // إذا كان نفس المتجر، إضافة المنتج للطلب الموجود
                        $existingOrderItem = OrderItem::where('order_id', $sameStoreOrder->id)
                            ->where('product_id', $request->product_id)
                            ->first();

                        if ($existingOrderItem) {
                            // إذا كان نفس المنتج، زيادة الكمية
                            $existingOrderItem->quantity += $request->quantity;
                            $discountedPrice = $this->calculateDiscountedPrice($product);
                            $existingOrderItem->price = $discountedPrice; // تحديث السعر في حالة تغير الخصم
                            $existingOrderItem->total_price = $existingOrderItem->quantity * $discountedPrice;
                            $existingOrderItem->save();
                        } else {
                            // إذا كان منتج مختلف من نفس المتجر، إضافة order item جديد
                            $discountedPrice = $this->calculateDiscountedPrice($product);
                            $totalPrice = $discountedPrice * $request->quantity;

                            OrderItem::create([
                                'order_id' => $sameStoreOrder->id,
                                'product_id' => $request->product_id,
                                'quantity' => $request->quantity,
                                'price' => $discountedPrice,
                                'total_price' => $totalPrice,
                            ]);
                        }

                        // تحديث إجمالي الطلب
                        $this->updateOrderTotals($sameStoreOrder);
                    } else {
                        // إذا كان متجر مختلف، حذف جميع الطلبات القديمة وإنشاء طلب جديد
                        foreach ($existingOrders as $order) {
                            // حذف order items أولاً
                            OrderItem::where('order_id', $order->id)->delete();
                            // حذف الطلب
                            $order->delete();
                        }

                        // إنشاء طلب جديد للمتجر الجديد
                        $discountedPrice = $this->calculateDiscountedPrice($product);
                        $subtotal = $discountedPrice * $request->quantity;
                        $total_price = $subtotal; // No discount initially
                        $cashback_amount = $total_price * $store->cashback_rate / 100;

                        $newOrder = Order::create([
                            'user_id' => $user->id,
                            'store_id' => $product->store_id,
                            'quantity' => $request->quantity,
                            'subtotal' => $subtotal,
                            'total_price' => $total_price,
                            'cashback_amount' => $cashback_amount,
                        ]);

                        OrderItem::create([
                            'order_id' => $newOrder->id,
                            'product_id' => $request->product_id,
                            'quantity' => $request->quantity,
                            'price' => $discountedPrice,
                            'total_price' => $subtotal,
                        ]);
                    }
                } else {
                    // لا توجد طلبات سابقة، إنشاء طلب جديد
                    $discountedPrice = $this->calculateDiscountedPrice($product);
                    $subtotal = $discountedPrice * $request->quantity;
                    $total_price = $subtotal; // No discount initially
                    $cashback_amount = $total_price * $store->cashback_rate / 100;

                    $order = Order::create([
                        'user_id' => $user->id,
                        'store_id' => $product->store_id,
                        'quantity' => $request->quantity,
                        'subtotal' => $subtotal,
                        'total_price' => $total_price,
                        'cashback_amount' => $cashback_amount,
                    ]);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $request->product_id,
                        'quantity' => $request->quantity,
                        'price' => $discountedPrice,
                        'total_price' => $subtotal,
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function calculateDiscountedPrice($product)
    {
        if ($product->discount && $product->discount > 0) {
            // حساب الخصم كنسبة مئوية
            $discountAmount = $product->price * ($product->discount / 100);
            return $product->price - $discountAmount;
        }
        return $product->price;
    }

    private function updateOrderTotals($order)
    {
        $orderItems = $order->orderItems;
        $totalQuantity = 0;
        $subtotal = 0;

        foreach ($orderItems as $item) {
            $totalQuantity += $item->quantity;
            $subtotal += $item->total_price;
        }

        // Calculate discount amount if discount is applied
        $discount_amount = 0;
        if ($order->discount_id) {
            $discount = Discount::find($order->discount_id);
            if ($discount) {
                $discount_amount = $this->calculateDiscountAmount($discount, $subtotal);
            }
        }

        // Calculate final total price after discount
        $total_price = $subtotal - $discount_amount;

        // Calculate cashback from final total_price (after discount)
        $store = User::find($order->store_id);
        $cashback_amount = $total_price * $store->cashback_rate / 100;

        $order->update([
            'quantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount_amount' => $discount_amount,
            'total_price' => $total_price,
            'cashback_amount' => $cashback_amount,
        ]);
    }

    public function removefromcart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $user = $request->user();
            $product = Product::find($request->product_id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $order = Order::where('user_id', $user->id)
                ->where('store_id', $product->store_id)
                ->where('status', 'pending')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('product_id', $request->product_id)
                ->first();

            if (!$orderItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in cart',
                ], 404);
            }

            DB::transaction(function () use ($order, $orderItem) {
                // حذف المنتج من الطلب
                $orderItem->delete();

                // فحص إذا كان هذا آخر منتج في الطلب
                $remainingItems = OrderItem::where('order_id', $order->id)->count();

                if ($remainingItems == 0) {
                    // إذا لم يبق أي منتج، حذف الطلب بالكامل
                    $order->delete();
                } else {
                    // إذا بقي منتجات أخرى، تحديث إجمالي الطلب
                    $this->updateOrderTotals($order);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatequantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:1000',
        ]);

        try {
            $user = $request->user();
            $product = Product::find($request->product_id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $order = Order::where('user_id', $user->id)
                ->where('store_id', $product->store_id)
                ->where('status', 'pending')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('product_id', $request->product_id)
                ->first();

            if (!$orderItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in cart',
                ], 404);
            }

            DB::transaction(function () use ($orderItem, $request, $order) {
                // تحديث الكمية
                $orderItem->quantity = $request->quantity;
                $orderItem->total_price = $orderItem->quantity * $orderItem->price;
                $orderItem->save();

                // تحديث إجمالي الطلب
                $this->updateOrderTotals($order);
            });

            return response()->json([
                'success' => true,
                'message' => 'Product quantity updated successfully',
                'data' => [
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'total_price' => $orderItem->total_price,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product quantity',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeallfromcart(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)
        ->where('status', 'pending')
        ->get();
        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No pending orders found',
            ], 404);
        }
        foreach ($orders as $order) {
            OrderItem::where('order_id', $order->id)->delete();
            Transaction::where('order_id', $order->id)->delete();
            $order->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'All products removed from cart successfully',
        ]);
    }
    public function getgovernorates(Request $request)
    {
        $governorates = Governorate::all();
        return response()->json([
            'success' => true,
            'data' => $governorates,
        ]);
    }

    public function getcitiesbygovernorate($id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Governorate not found',
            ], 404);
        }
        $cities = City::where('governorate_id', $id)->get();
        return response()->json([
            'success' => true,
            'data' => $cities,
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        try {
            $user = $request->user();
            $order = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending order found',
                ], 404);
            }

            // Use the final total_price (after discount) for transaction
            $final_amount = $order->total_price;

            DB::transaction(function () use ($user, $order, $final_amount, $request) {
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'store_id' => $order->store_id,
                    'order_id' => $order->id,
                    'amount' => $final_amount,
                    'transaction_type' => 'checkout',
                    'payment_method' => 'cash',
                ]);

                $order->status = 'processing';
                $order->city_id = $request->city_id;
                $order->address = $request->address;
                $order->phone = $request->phone;
                $order->payment_method = 'cash';
                $order->save();

                // Handle wallet cashback
                $wallet = Wallet::where('user_id', $user->id)
                    ->where('store_id', $order->store_id)
                    ->first();

                if (!$wallet) {
                    $wallet = Wallet::create([
                        'user_id' => $user->id,
                        'balance' => $order->cashback_amount,
                        'store_id' => $order->store_id,
                    ]);
                } else {
                    $wallet->balance += $order->cashback_amount;
                    $wallet->save();
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Checkout successful',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete checkout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Apply discount code to order
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string',
        ]);

        try {
            $user = $request->user();
            $order = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending order found',
                ], 404);
            }

            $discount = Discount::where('code', $request->discount_code)
                ->where('active', true)
                ->first();

            if (!$discount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid discount code',
                ], 400);
            }

            // Validate discount
            $validation = $this->validateDiscount($discount, $order, $user);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message'],
                ], 400);
            }

            // Apply discount to order
            $order->discount_id = $discount->id;
            $order->save();

            // Recalculate order totals with discount
            $this->updateOrderTotals($order);

            // Increment discount usage
            // $discount->increment('uses');

            return response()->json([
                'success' => true,
                'message' => 'Discount applied successfully',
                'discount' => [
                    'code' => $discount->code,
                    'type' => $discount->type,
                    'value' => $discount->value,
                    'discount_amount' => $order->discount_amount,
                ],
                'order' => [
                    'subtotal' => $order->subtotal,
                    'discount_amount' => $order->discount_amount ?? 0,
                    'total_price' => $order->total_price,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply discount',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove discount from order
     */
    public function removeDiscount(Request $request)
    {
        try {
            $user = $request->user();
            $order = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending order found',
                ], 404);
            }

            if (!$order->discount_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No discount applied to this order',
                ], 400);
            }

            // Decrement discount usage
            $discount = Discount::find($order->discount_id);
            if ($discount) {
                $discount->decrement('uses');
            }

            // Remove discount from order
            $order->discount_id = null;
            $order->discount_amount = null;
            $order->save();

            // Recalculate order totals without discount
            $this->updateOrderTotals($order);

            return response()->json([
                'success' => true,
                'message' => 'Discount removed successfully',
                'order' => [
                    'subtotal' => $order->subtotal,
                    'discount_amount' => 0,
                    'total_price' => $order->total_price,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove discount',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available discounts for current order
     */
    public function getAvailableDiscounts(Request $request)
    {
        try {
            $user = $request->user();
            $order = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->with('orderItems.product')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending order found',
                ], 404);
            }

            $now = Carbon::now();
            $availableDiscounts = Discount::where('active', true)
                ->where(function ($query) use ($now) {
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', $now);
                })
                ->where(function ($query) use ($now) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                })
                ->where(function ($query) {
                    $query->whereNull('max_uses')
                        ->orWhereRaw('uses < max_uses');
                })
                ->get()
                ->filter(function ($discount) use ($order, $user) {
                    $validation = $this->validateDiscount($discount, $order, $user);
                    return $validation['valid'];
                })
                ->map(function ($discount) use ($order) {
                    return [
                        'id' => $discount->id,
                        'code' => $discount->code,
                        'type' => $discount->type,
                        'value' => $discount->value,
                        'scope_type' => $discount->scope_type,
                        'scope_names' => $discount->scope_names,
                        'discount_amount' => $this->calculateDiscountAmount($discount, $order->subtotal),
                        'start_date' => $discount->start_date,
                        'end_date' => $discount->end_date,
                        'max_uses' => $discount->max_uses,
                        'uses' => $discount->uses,
                    ];
                });

            return response()->json([
                'success' => true,
                'discounts' => $availableDiscounts,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available discounts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate discount eligibility
     */
    private function validateDiscount($discount, $order, $user)
    {
        // Check if discount is active
        if (!$discount->active) {
            return ['valid' => false, 'message' => 'Discount is not active'];
        }

        // Check date validity
        $now = Carbon::now();
        if ($discount->start_date && $now->lt($discount->start_date)) {
            return ['valid' => false, 'message' => 'Discount is not yet active'];
        }
        if ($discount->end_date && $now->gt($discount->end_date)) {
            return ['valid' => false, 'message' => 'Discount has expired'];
        }

        // Check usage limits
        if ($discount->max_uses && $discount->uses >= $discount->max_uses) {
            return ['valid' => false, 'message' => 'Discount usage limit reached'];
        }

        // Check per-user usage limit
        if ($discount->max_uses_per_user) {
            $userUsage = Order::where('user_id', $user->id)
                ->where('discount_id', $discount->id)
                ->count();
            if ($userUsage >= $discount->max_uses_per_user) {
                return ['valid' => false, 'message' => 'You have reached the maximum usage limit for this discount'];
            }
        }

        // Check scope validity
        if ($discount->scope_type === 'product') {
            $orderProductIds = $order->orderItems->pluck('product_id')->toArray();
            $discountProductIds = $discount->products->pluck('id')->toArray();

            if (empty(array_intersect($orderProductIds, $discountProductIds))) {
                return ['valid' => false, 'message' => 'This discount is not applicable to products in your order'];
            }
        } elseif ($discount->scope_type === 'category') {
            $orderProductIds = $order->orderItems->pluck('product_id')->toArray();
            $orderCategories = Product::whereIn('id', $orderProductIds)
                ->with('categories')
                ->get()
                ->pluck('categories')
                ->flatten()
                ->pluck('id')
                ->unique()
                ->toArray();

            $discountCategoryIds = $discount->categories->pluck('id')->toArray();

            if (empty(array_intersect($orderCategories, $discountCategoryIds))) {
                return ['valid' => false, 'message' => 'This discount is not applicable to categories in your order'];
            }
        }

        return ['valid' => true, 'message' => 'Valid discount'];
    }

    /**
     * Calculate discount amount for order
     */
    private function calculateDiscountAmount($discount, $subtotal)
    {
        if ($discount->type === 'percentage') {
            return $subtotal * ($discount->value / 100);
        } else {
            return min($discount->value, $subtotal);
        }
    }



    public function myorders(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)->with('orderItems.product', 'store', 'discount')->get();
        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
