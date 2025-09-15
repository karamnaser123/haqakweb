<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_products');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'discount_categories');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Helper method to get scope type
    public function getScopeTypeAttribute()
    {
        if ($this->products()->count() > 0) {
            return 'product';
        } elseif ($this->categories()->count() > 0) {
            return 'category';
        } else {
            return 'general';
        }
    }

    // Helper method to get scope names
    public function getScopeNamesAttribute()
    {
        if ($this->products()->count() > 0) {
            return $this->products->pluck('name_en')->join(', ');
        } elseif ($this->categories()->count() > 0) {
            return $this->categories->pluck('name_en')->join(', ');
        } else {
            return 'All Products';
        }
    }
}
