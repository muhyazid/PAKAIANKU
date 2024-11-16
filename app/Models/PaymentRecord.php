<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    use HasFactory;
    protected $table = 'payment_records';
    protected $fillable = [
        'rfq_id',
        'amount',
        'payment_method',
        'payment_date'
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }
}
