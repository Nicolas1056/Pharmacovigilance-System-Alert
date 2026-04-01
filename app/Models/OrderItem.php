<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    protected $fillable = ['order_id', 'medication_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
