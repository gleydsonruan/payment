<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function increaseBalance($value)
    {
        return $this->increment('balance', $value);
    }

    public function decreaseBalance($value)
    {
        return $this->decrement('balance', $value);
    }
}
