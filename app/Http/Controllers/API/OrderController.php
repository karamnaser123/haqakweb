<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;

class OrderController extends Controller
{
    public function cart(Request $request)
    {
        $user = $request->user();
        $cart = Order::where('user_id', $user->id)->with('orderItems.product.productImages', 'store')->get();
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
                $existingOrders = Order::where('user_id', $user->id)->get();

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
                            $existingOrderItem->total_price = $existingOrderItem->quantity * $existingOrderItem->price;
                            $existingOrderItem->save();
                        } else {
                            // إذا كان منتج مختلف من نفس المتجر، إضافة order item جديد
                            OrderItem::create([
                                'order_id' => $sameStoreOrder->id,
                                'product_id' => $request->product_id,
                                'quantity' => $request->quantity,
                                'price' => $product->price,
                                'total_price' => $product->price * $request->quantity,
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
                        $total_price = $product->price * $request->quantity;
                        $cashback_amount = $total_price * $store->cashback_rate / 100;

                        $newOrder = Order::create([
                            'user_id' => $user->id,
                            'store_id' => $product->store_id,
                            'quantity' => $request->quantity,
                            'total_price' => $total_price,
                            'cashback_amount' => $cashback_amount,
                        ]);

                        OrderItem::create([
                            'order_id' => $newOrder->id,
                            'product_id' => $request->product_id,
                            'quantity' => $request->quantity,
                            'price' => $product->price,
                            'total_price' => $total_price,
                        ]);
                    }
                } else {
                    // لا توجد طلبات سابقة، إنشاء طلب جديد
                    $total_price = $product->price * $request->quantity;
                    $cashback_amount = $total_price * $store->cashback_rate / 100;

                    $order = Order::create([
                        'user_id' => $user->id,
                        'store_id' => $product->store_id,
                        'quantity' => $request->quantity,
                        'total_price' => $total_price,
                        'cashback_amount' => $cashback_amount,
                    ]);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $request->product_id,
                        'quantity' => $request->quantity,
                        'price' => $product->price,
                        'total_price' => $total_price,
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

    private function updateOrderTotals($order)
    {
        $orderItems = $order->orderItems;
        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($orderItems as $item) {
            $totalQuantity += $item->quantity;
            $totalPrice += $item->total_price;
        }

        $store = User::find($order->store_id);

        $cashback_amount = $totalPrice * $store->cashback_rate / 100;

        $order->update([
            'quantity' => $totalQuantity,
            'total_price' => $totalPrice,
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
            'quantity' => 'required|integer|min:1',
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

    public function checkout(Request $request)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)
        ->where('status', 'pending')
        ->first();
        $total_price = $order->total_price;
        $cashback_amount = $total_price * $user->cashback_rate / 100;
        $total_price = $total_price - $cashback_amount;
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'store_id' => $order->store_id,
            'amount' => $total_price,
            'transaction_type' => 'checkout',
            'payment_method' => 'cash',
        ]);
        $order->status = 'processing';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Checkout successful',
        ]);
    }
}
