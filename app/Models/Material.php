<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
     protected $table = 'materials';
    protected $fillable = [
        'nama_bahan', 
        'kuantitas', 
        'satuan', 
        'image',
        'price',
        'product_cost', 
        'produk_id',
        'supplier_id',
    ];

    public function boms()
    {
        return $this->belongsToMany(BoM::class, 'bom_material', 'material_id', 'bom_id')
                    ->withPivot('quantity', 'unit')
                    ->withTimestamps();
    }

    public function manufacturingOrders()
    {
        return $this->belongsToMany(ManufacturingOrder::class, 'manufacturing_order_materials')
                    ->withPivot('to_consume', 'quantity')
                    ->withTimestamps();
    }

    public function suppliers()
    {
        return $this->belongsTo(suppliers::class);
    }

}
