<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        $categories = Category::whereNotNull('parent_id')->get();
        $stores = User::whereHasRole('store')->get();
        return $dataTable->render('admin.products.index', compact('categories', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'store_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'active' => 'nullable|in:on,1,true',
            'featured' => 'nullable|in:on,1,true',
            'new' => 'nullable|in:on,1,true',
            'best_seller' => 'nullable|in:on,1,true',
            'top_rated' => 'nullable|in:on,1,true',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->all();

        // Handle boolean fields
        $data['active'] = $request->has('active') && $request->active === 'on' ? 1 : 0;
        $data['featured'] = $request->has('featured') && $request->featured === 'on' ? 1 : 0;
        $data['new'] = $request->has('new') && $request->new === 'on' ? 1 : 0;
        $data['best_seller'] = $request->has('best_seller') && $request->best_seller === 'on' ? 1 : 0;
        $data['top_rated'] = $request->has('top_rated') && $request->top_rated === 'on' ? 1 : 0;

        $product = Product::create($data);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                $product->productImages()->create([
                    'image' => $imagePath,
                    'is_primary' => false
                ]);
            }

            // Set first image as primary
            if ($product->productImages->count() > 0) {
                $product->productImages->first()->update(['is_primary' => true]);
            }
        }

        return response()->json([
            'success' => __('Product created successfully'),
        ]);
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'store', 'productImages'])->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }


        return response()->json([
            'data' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'store_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'active' => 'nullable|in:on,1,true',
            'featured' => 'nullable|in:on,1,true',
            'new' => 'nullable|in:on,1,true',
            'best_seller' => 'nullable|in:on,1,true',
            'top_rated' => 'nullable|in:on,1,true',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->all();

        // Handle boolean fields
        $data['active'] = $request->active === 'on' ? 1 : 0;
        $data['featured'] = $request->featured === 'on' ? 1 : 0;
        $data['new'] = $request->new === 'on' ? 1 : 0;
        $data['best_seller'] = $request->best_seller === 'on' ? 1 : 0;
        $data['top_rated'] = $request->top_rated === 'on' ? 1 : 0;

        $product->update($data);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                $product->productImages()->create([
                    'image' => $imagePath,
                    'is_primary' => false
                ]);
            }
        }

        return response()->json([
            'success' => __('Product updated successfully'),
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete product images
        foreach ($product->productImages as $image) {
            if ($image->image) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }

        $product->delete();
        return response()->json([
            'success' => __('Product deleted successfully'),
        ]);
    }

    public function deleteImage($id)
    {
        $image = ProductImage::find($id);
        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return response()->json([
            'success' => __('Image deleted successfully'),
        ]);
    }
}
