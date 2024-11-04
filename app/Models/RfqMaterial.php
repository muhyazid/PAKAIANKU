<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['rfq_id', 'material_id', 'spesifikasi', 'satuan', 'kuantitas'];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
