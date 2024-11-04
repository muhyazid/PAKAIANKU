<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use HasFactory;
    protected $fillable = ['kode_rfq', 'supplier_id', 'tanggal_penawaran'];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function rfqMaterials()
    {
        return $this->hasMany(RfqMaterial::class);
    }
}
