<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'PENDING';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_FAILED = 'FAILED';
    
    protected $fillable = [
        'payer',
        'payee',
        'value',
        'status',
    ];

    public function payer()
    {
        return $this->belongsTo('App\Models\Account', 'payer');
    }

    public function payee()
    {
        return $this->belongsTo('App\Models\Account', 'payee');
    }
}
