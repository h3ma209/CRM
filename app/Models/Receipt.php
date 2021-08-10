<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Receipt extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['date', 'paid_at', 'invoice_id', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(ReceiptDetail::class);
    }

    public function totalDetails()
    {
        return $this->hasMany(ReceiptDetail::class)->select(DB::raw('SUM(quantity * price) AS total'), 'receipt_id')->groupBy('receipt_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['date', 'paid_at', 'invoice_id', 'customer_id']);
        // Chain fluent methods for configuration options
    }
}
