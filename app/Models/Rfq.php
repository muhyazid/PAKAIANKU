<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use HasFactory;
    protected $table = 'rfqs';
    
    protected $fillable = [
        'rfq_code',
        'supplier_id',
        'quotation_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'quotation_date' => 'date'
    ];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(RfqItem::class);
    }
}
