<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CustomerCredentials extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $fillable = ['user_id', 'contract_id', 'username', 'password', 'note'];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'contract_id', 'username', 'password', 'note']);
        // Chain fluent methods for configuration options
    }
}
