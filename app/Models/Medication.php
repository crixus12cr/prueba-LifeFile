<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'name', 
        'lot_number'
    ];

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
