<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }

    // علاقة مع التخصيصات
    public function customizations()
    {
        return $this->hasMany(Customization::class);
    }

    // علاقة مع عناصر الطلبات
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // علاقة مع المراجعات
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
