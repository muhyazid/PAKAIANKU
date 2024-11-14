<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqItem extends Model
{
    use HasFactory;
    protected $table = 'rfq_items';
    
    protected $fillable = [
        'rfq_id',
        'material_id',
        'quantity',
        'unit',
        'material_price',  // Menyimpan harga satuan dari material
        'subtotal' 
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
