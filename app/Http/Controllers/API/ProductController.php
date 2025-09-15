<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function categories()
    {
        $categories = Category::whereNull('parent_id')
            ->withCount(['children'])
            ->paginate(10);

        return response()->json([
            'categories' => $categories,
        ]);
    }
    public function subcategories($id)
    {
        $categories = Category::where('parent_id', $id)
            ->withCount('products')
            ->paginate(10);
        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function productbycategory($id)
    {
        $products = Product::where('category_id', $id)
        ->with( 'productImages','store')
        ->paginate(10);
        $products_count = Product::where('category_id', $id)
        ->count();

        return response()->json([
            'products' => $products,
            'products_count' => $products_count,
        ]);
    }

    public function productdetails($id)
    {
        $product = Product::with('category', 'store', 'productImages')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json([
            'product' => $product,
        ]);
    }

    public function stores()
    {
        $stores = User::whereHasRole('store')
        ->with(['store'])
        ->paginate(10);
        return response()->json([
            'stores' => $stores,
        ]);
    }

    public function productsbystore($id)
    {
        $products = Product::where('store_id', $id)->with('productImages', 'store')->get();
        return response()->json([
            'products' => $products,
        ]);
    }
}
