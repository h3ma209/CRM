<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptDetail extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['receipt_id', 'name', 'price', 'quantity', 'note'];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
