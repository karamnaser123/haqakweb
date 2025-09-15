<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\DataTables\DiscountDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class DiscountController extends Controller
{
    public function index(DiscountDataTable $dataTable)
    {
        $products = Product::all();
        $categories = Category::all();
        return $dataTable->render('admin.discounts.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:discounts,code',
            'value' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_uses' => 'nullable|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        // Ensure only one scope is selected
        if ($request->product_ids && $request->category_ids) {
            return response()->json([
                'error' => __('Please select either products or categories, not both.'),
            ], 422);
        }

        $data = $request->all();

        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = strtoupper(Str::random(8));
        }

        // Ensure code is unique
        while (Discount::where('code', $data['code'])->exists()) {
            $data['code'] = strtoupper(Str::random(8));
        }

        // Remove pivot data from main data
        $productIds = $data['product_ids'] ?? [];
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['product_ids'], $data['category_ids']);

        // Create the discount
        $discount = Discount::create($data);

        // Attach products if selected
        if (!empty($productIds)) {
            $discount->products()->attach($productIds);
        }

        // Attach categories if selected
        if (!empty($categoryIds)) {
            $discount->categories()->attach($categoryIds);
        }

        return response()->json([
            'success' => __('Discount created successfully'),
        ]);
    }

    public function edit($id)
    {
        $discount = Discount::with(['products', 'categories'])->find($id);
        if (!$discount) {
            return response()->json(['error' => 'Discount not found'], 404);
        }

        $products = Product::all();
        $categories = Category::all();

        // Add selected product and category IDs to the response
        $discountData = $discount->toArray();
        $discountData['product_ids'] = $discount->products->pluck('id')->toArray();
        $discountData['category_ids'] = $discount->categories->pluck('id')->toArray();

        return response()->json([
            'data' => $discountData,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::find($id);
        if (!$discount) {
            return response()->json(['error' => 'Discount not found'], 404);
        }

        $request->validate([
            'code' => 'nullable|string|max:255|unique:discounts,code,' . $id,
            'value' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_uses' => 'nullable|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        // Ensure only one scope is selected
        if ($request->product_ids && $request->category_ids) {
            return response()->json([
                'error' => __('Please select either products or categories, not both.'),
            ], 422);
        }

        $data = $request->all();

        // Remove pivot data from main data
        $productIds = $data['product_ids'] ?? [];
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['product_ids'], $data['category_ids']);

        // Update the discount
        $discount->update($data);

        // Sync products
        if (!empty($productIds)) {
            $discount->products()->sync($productIds);
        } else {
            $discount->products()->detach();
        }

        // Sync categories
        if (!empty($categoryIds)) {
            $discount->categories()->sync($categoryIds);
        } else {
            $discount->categories()->detach();
        }

        return response()->json([
            'success' => __('Discount updated successfully'),
        ]);
    }

    public function destroy($id)
    {
        $discount = Discount::find($id);
        if (!$discount) {
            return response()->json(['error' => 'Discount not found'], 404);
        }

        $discount->delete();
        return response()->json([
            'success' => __('Discount deleted successfully'),
        ]);
    }
}
