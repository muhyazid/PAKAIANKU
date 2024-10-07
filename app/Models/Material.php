<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_bahan', 
        'kuantitas', 
        'satuan', 
        'stock',
        'image',
    ];

}
