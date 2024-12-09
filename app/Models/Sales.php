<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'sales_code',
        'customer_id',
        'billing_address',
        'shipping_address',
        'expiry_date',
        'status',
        'total_amount',
        'payment_method'
    ];

    // Definisikan konstanta untuk status
    const STATUS = [
        'QUOTATION' => 'quotation',
        'SALES_ORDER' => 'sales_order',
        'DELIVERED' => 'delivered',
        'DONE' => 'done'
    ];

     // Definisikan konstanta untuk metode pembayaran
    const PAYMENT_METHODS = [
        'CASH' => 'cash',
        'TRANSFER' => 'transfer'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }
    // Mutator untuk mengatur status
    public function setStatus($status)
    {
        if (!in_array($status, self::STATUS)) {
            throw new \InvalidArgumentException("Invalid status");
        }
        $this->attributes['status'] = $status;
    }

    // Mutator untuk mengatur metode pembayaran
    public function setPaymentMethod($method)
    {
        if (!in_array($method, self::PAYMENT_METHODS)) {
            throw new \InvalidArgumentException("Invalid payment method");
        }
        $this->attributes['payment_method'] = $method;
    }
}
