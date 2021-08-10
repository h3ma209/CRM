<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptDetail extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['receipt_id', 'name', 'price', 'quantity', 'currency', 'note'];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['receipt_id', 'name', 'price', 'quantity', 'currency', 'note']);
        // Chain fluent methods for configuration options
    }
}
