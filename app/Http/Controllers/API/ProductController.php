<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function categories()
    {
        $categories = Category::paginate(10);
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
}
