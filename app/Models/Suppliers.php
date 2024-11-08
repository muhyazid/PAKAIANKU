<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'no_tlp', 'alamat', 'material_id'];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    // public function materials()
    // {
    //     return $this->hasMany(Material::class, 'supplier_id');
    // }

    
}
