<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Contract extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['user_id', 'customer_id', 'start_date', 'expire_date', 'prepayment', 'prepayment_currency', 'monthly_payment', 'monthly_payment_currency', 'user_quantity', 'note', 'deleted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function credentials()
    {
        return $this->hasMany(CustomerCredentials::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'customer_id', 'start_date', 'expire_date', 'prepayment', 'prepayment_currency', 'monthly_payment', 'monthly_payment_currency', 'user_quantity', 'note', 'deleted_at']);
        // Chain fluent methods for configuration options
    }
}
