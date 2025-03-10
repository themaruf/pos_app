<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'sale_payments';
    protected $guarded = ['id'];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}