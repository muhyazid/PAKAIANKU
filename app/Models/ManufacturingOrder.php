<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingOrder extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'quantity', 'start_date', 'end_date', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'manufacturing_order_materials')
                    ->withPivot('to_consume', 'quantity', 'consumed')
                    ->withTimestamps();
    }
}
