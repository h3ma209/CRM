<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Customer extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['user_id', 'name', 'address', 'contact_1', 'contact_2', 'free_trial', 'note', 'deleted_at'];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'name', 'address', 'contact_1', 'contact_2', 'free_trial', 'note', 'deleted_at']);
    }
}
