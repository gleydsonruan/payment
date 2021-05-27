<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'PENDING';
    const STATUS_CONFIRMED = 'CONFIRMED';
    
    protected $fillable = [
        'payer_id',
        'payee_id',
        'value',
        'status',
    ];

    public function payer()
    {
        return $this->belongsTo('App\Models\Account', 'payer_id');
    }

    public function payee()
    {
        return $this->belongsTo('App\Models\Account', 'payee_id');
    }

    public function confirmPayment()
    {
        $this->status = self::STATUS_CONFIRMED;
        return $this->save();
    }
}
