<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoMMaterial extends Model
{
    protected $table = 'bom_material';

    protected $fillable = ['bom_id', 'material_id', 'quantity', 'unit'];

    public function bom()
    {
        return $this->belongsTo(BoM::class, 'bom_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
