<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
    ];

    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع عناصر الطلبات
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'order_items')
                        ->withPivot('quantity', 'price')
                        ->withTimestamps();
    }
}
