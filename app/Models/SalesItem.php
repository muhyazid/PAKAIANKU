<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;
    protected $table = 'sales_items';
    protected $fillable = [
        'sales_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Mutator untuk menghitung subtotal
    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->price;
        return $this->subtotal;
    }
}
