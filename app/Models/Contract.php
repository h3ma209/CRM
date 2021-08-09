<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'customer_id', 'start_date', 'expire_date', 'prepayment', 'prepayment_currency', 'monthly_payment', 'monthly_payment_currency', 'user_quantity', 'note', 'deleted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
