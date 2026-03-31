<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'customer_id', 
        'order_id', 
        'user_id', 
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
