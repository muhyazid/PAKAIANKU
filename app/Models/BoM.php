<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoM extends Model
{
    protected $table = 'boms';

    protected $fillable = [
        'product_id', 'production_code', 'quantity'
    ];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'bom_material', 'bom_id', 'material_id')
                    ->withPivot('quantity', 'unit');
    }

    // Model BoM (BoM.php)
    public function product()
    {
        return $this->belongsTo(Product::class, foreignKey: 'product_id');
    }

}
