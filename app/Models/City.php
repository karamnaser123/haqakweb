<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['governorate_id', 'name_en', 'name_ar'];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
