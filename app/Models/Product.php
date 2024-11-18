<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['nama_produk', 'kategori', 'deskripsi', 'image_path', 'stock'];
    
    public function boms()
    {
        return $this->hasMany(BoM::class);
    }

    public function manufacturingOrders()
    {
        return $this->hasMany(ManufacturingOrder::class);
    }

    // Method untuk menambah stok produk
    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();
    }
}
